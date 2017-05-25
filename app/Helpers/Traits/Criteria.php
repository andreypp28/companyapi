<?php

namespace WA\Helpers\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;
use WA\DataStore\BaseDataStore;
use WA\Exceptions\BadCriteriaException;
use WA\Http\Requests\Parameters\Fields;
use WA\Http\Requests\Parameters\Filters;
use WA\Http\Requests\Parameters\Sorting;

trait Criteria
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|BaseDataStore
     */
    protected $criteriaModel;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $criteriaQuery;

    /**
     * @var Sorting
     */
    protected $sortCriteria = null;

    /**
     * @var Filters
     */
    protected $filterCriteria = null;

    /**
     * @var array
     */
    protected $criteria = [
        'sort'    => [],
        'filters' => [],
        'fields'  => []
    ];

    /**
     * @var Filters
     */
    protected $filters = null;

    /**
     * @var Sorting
     */
    protected $sort = null;

    /**
     * @var Fields
     */
    protected $fields = null;

    public $criteriaModelName = null;
    protected $criteriaModelColumns = null;

    protected $isInclude = false;

    /**
     * We have to map some table names / model names because they aren't totally named right
     *
     * @var array
     */
    protected $modelMap = null;


    /**
     * CriteriaTransformer constructor.
     *
     * @param array $criteria
     */
    public function __construct($criteria = [])
    {
        $this->criteria = $criteria;
    }

    /**
     * Get a query-builder instance for this model.
     *
     * @param $criteriaModel
     * @param bool $clear
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery($criteriaModel, $clear = false)
    {
        if ($clear == true) {
            $this->criteriaQuery = null;
        }
        if ($this->criteriaQuery === null) {
            if ($criteriaModel instanceof Relation) {
                $this->criteriaQuery = $criteriaModel;
                $this->criteriaModelName = $criteriaModel->getRelated()->getTable();
                if (method_exists($criteriaModel->getRelated(), 'getTableColumns')) {
                    $this->criteriaModelColumns = $criteriaModel->getRelated()->getTableColumns();
                }
            } elseif ($criteriaModel instanceof BaseDataStore) {
                $this->criteriaQuery = $criteriaModel->newQuery();
                $this->criteriaModelName = $criteriaModel->getTable();
                $this->criteriaModelColumns = $criteriaModel->getTableColumns();
            }
        }

        return $this->criteriaQuery;
    }

    /**
     * Convenience method to set all criteria at once.
     *
     * @param array $criteria
     *
     * @return bool
     */
    public function setCriteria($criteria = [])
    {
        if (isset($criteria['sort'])) {
            $this->setSort($criteria['sort']);
        }

        if (isset($criteria['filters'])) {
            $this->setFilters($criteria['filters']);
        }

        if (isset($criteria['fields'])) {
            $this->setFields($criteria['fields']);
        }

        return true;
    }

    /**
     * Set sort criteria.
     *
     * @param Sorting $sortCriteria
     *
     * @return $this
     */
    public function setSort(Sorting $sortCriteria)
    {
        if ($sortCriteria !== null) {
            $this->sortCriteria = $sortCriteria;
        }

        return $this;
    }

    /**
     * Set filter criteria.
     *
     * @param Filters $filterCriteria
     *
     * @return $this
     */
    public function setFilters(Filters $filterCriteria)
    {
        if ($filterCriteria !== null) {
            $this->filterCriteria = $filterCriteria;
        }

        return $this;
    }

    /**
     * Set fields criteria.
     *
     * @param Filters $fieldCriteria
     *
     * @return $this
     */
    public function setFields(Fields $fieldCriteria)
    {
        if ($fieldCriteria !== null) {
            $this->fieldCriteria = $fieldCriteria;
        }

        return $this;
    }

    /**
     * @param $criteriaModel
     * @param null $criteria Optional criteria
     * @param bool $isInclude Optional Is this an include?
     * @param null $modelMap Optional model table-name mapping for non-standard table names
     * @param bool $returnEmptyResults Whether to return empty children result-sets or not
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyCriteria(
        $criteriaModel,
        $criteria = null,
        $isInclude = false,
        $modelMap = null,
        $returnEmptyResults = false
    ) {
        if ($criteria !== null) {
            $this->setCriteria($criteria);
        }

        $this->returnEmptyResults = $returnEmptyResults;
        $this->isInclude = $isInclude;
        $this->modelMap = $modelMap;
        $this->criteriaModel = $criteriaModel;

        $this->getQuery($criteriaModel, true);

        $this->sort()->filter($returnEmptyResults);

        return $this->criteriaQuery;
    }


    /**
     * Apply filter criteria to the current query.
     *
     * @return $this
     *
     * @throws BadCriteriaException
     */
    protected function filter($returnEmptyResults = false)
    {
        if ($this->filterCriteria === null) {
            return $this;
        }

        $criteriaModelName = $this->criteriaModelName;
        $criteriaModelColumns = $this->criteriaModelColumns;

        foreach ($this->filterCriteria->filtering() as $filterKey => $filterVal) {
            if(is_int($filterKey)) {
                // CASO OR
                if(isset($filterVal['eq'])) {
                    if(strpos($filterVal['eq'], "[or]")) {
                        $parts = explode("[or]" , $filterVal['eq']);
                        $type = 'AND';

                        $arrayFilters = $this->retrieveInformationInAnArray($parts);
                        $needIncludes = $this->arrayNeedsIncludes($arrayFilters);

                        if($needIncludes) {
                            $model = $this->returnTheCriteriaModel($criteriaModelName);
                            $transformer = $this->createTransformer($model);
                            $newTransformer = new $transformer();

                            $ok = true;
                            foreach ($arrayFilters as $key => $value) {
                                $ok = $this->includesAreCorrectInf($value['relKey'], $newTransformer);
                            }

                            if ($ok) {
                                $this->criteriaQuery->whereHas($arrayFilters[0]['relKey'],
                                    function ($query) use ($arrayFilters, $type) {
                                        foreach ($arrayFilters as $key => $value) {
                                            $parts = explode('.', $value['relKey']);
                                            $relKey = $parts[count($parts) - 1];
                                            $query = $this->executeCriteria($query, $this->changeTableName($relKey) . "." . $value['relColumn'], $value['operation'], $value['value'], $type);
                                            $type = 'OR';
                                        }
                                    return $query;
                                });
                            }
                        } else {
                            foreach ($arrayFilters as $key => $value) {
                                $this->criteriaQuery = $this->executeCriteria($this->criteriaQuery, $value['relColumn'], $value['operation'], $value['value'], $type);
                                $type = 'OR';
                            }
                        }
                    } else if (strpos($filterVal['eq'], "[and]")) {
                        $parts = explode("[and]" , $filterVal['eq']);

                        $arrayFilters = $this->retrieveInformationInAnArray($parts);
                        $needIncludes = $this->arrayNeedsIncludes($arrayFilters);

                        if($needIncludes) {
                            $model = $this->returnTheCriteriaModel($criteriaModelName);
                            $transformer = $this->createTransformer($model);
                            $newTransformer = new $transformer();

                            $ok = true;
                            foreach ($arrayFilters as $key => $value) {
                                $ok = $this->includesAreCorrectInf($value['relKey'], $newTransformer);
                            }

                            if ($ok) {
                                $this->criteriaQuery->whereHas($arrayFilters[0]['relKey'],
                                    function ($query) use ($arrayFilters) {
                                        foreach ($arrayFilters as $key => $value) {
                                            $query = $this->executeCriteria($query, $this->changeTableName($value['relKey']) . "." . $value['relColumn'], $value['operation'], $value['value'], 'AND');
                                        }
                                    return $query;
                                });
                            }
                        } else {
                            foreach ($arrayFilters as $key => $value) {
                                $this->criteriaQuery = $this->executeCriteria($this->criteriaQuery, $value['relColumn'], $value['operation'], $value['value'], 'AND');
                            }
                        }
                    } else {
                        throw new BadCriteriaException('Invalid filter criteria, malformed OR filter');
                    }
                }
            } else {
                // CASO AND
                if (strpos($filterKey, '.')) {
                    $parts = explode('.', $filterKey);
                    $relColumn = $parts[count($parts) - 1];
                    $relKey = '';
                    $parts = array_slice($parts, 0, count($parts) - 1);
                    
                    foreach ($parts as $part) {
                        if ($relKey == '') {
                            $relKey = $part;   
                        } else {
                            $relKey = $relKey . '.' . $part;
                        }                        
                    }

                    if (is_array($this->modelMap) && isset($this->modelMap[$relKey])) {
                        $relKey = $this->modelMap[$relKey];
                    }

                    if ($relKey !== $criteriaModelName) {
                        if ($returnEmptyResults === false) {
                            $op = strtolower(key($filterVal));
                            $val = current($filterVal);

                            $model = $this->returnTheCriteriaModel($criteriaModelName);
                            $transformer = $this->createTransformer($model);
                            $newTransformer = new $transformer();

                            if ($this->includesAreCorrectInf($relKey, $newTransformer)) {
                                $this->criteriaQuery->whereHas($relKey,
                                    function ($query) use ($relKey, $relColumn, $op, $val) {
                                        $parts = explode('.', $relKey);
                                        $relKey = $parts[count($parts) - 1];
                                        return $query = $this->executeCriteria($query, $this->changeTableName($relKey) . "." . $relColumn, $op, $val, 'AND');
                                });
                            }
                        }
                        continue;
                    }

                    $filterKey = $relColumn;
                } else {

                    if (in_array($filterKey, $criteriaModelColumns)) {
                        if (is_array($filterVal)) {
                            foreach ($filterVal as $op => $val) {
                                $this->criteriaQuery = $this->executeCriteria($this->criteriaQuery, $this->changeTableName($filterKey), $op, $val, 'AND');
                            }
                        } else {
                            $op = strtolower(key($filterVal));
                            $val = current($filterVal);
                            $this->criteriaQuery = $this->executeCriteria($this->criteriaQuery, $this->changeTableName($filterKey), $op, $val, 'AND');
                        }
                    } else {
                        throw new BadCriteriaException('Invalid filter criteria');
                    }
                }
            }
        }

        //dd($this->criteriaQuery->toSql());
        //\Log::debug("Criteria@filter - this->criteriaQuery->toSql(): " . print_r($this->criteriaQuery->toSql(), true));

        return $this;
    }

    private function returnTheCriteriaModel($model) {
        $values = explode("_", $model);
        if (count($values) == 2) {
            return $values[0].$values[1];
        }
        return $model;
    }

    private function createTransformer($var)
    {
        if($var === 'categoryapps') { return "\\WA\\DataStore\\Category\\CategoryAppTransformer"; }
        if($var === 'devicetypes') { return "\\WA\\DataStore\\DeviceType\\DeviceTypeTransformer"; }
        if($var === 'devicevariations') { return "\\WA\\DataStore\\DeviceVariation\\DeviceVariationTransformer"; }
        if($var === 'serviceitems') { return "\\WA\\DataStore\\ServiceItem\\ServiceItemTransformer"; }
        if($var === 'udlvalues') { return "\\WA\\DataStore\\UdlValue\\UdlValueTransformer"; }

        $model = title_case(str_singular($var));
        return "\\WA\\DataStore\\${model}\\${model}Transformer";
    }

    public function includesAreCorrectInf($include, $class)
    {
        $includesAvailable = $class->getAvailableIncludes();

        if (strpos($include, '.')) {
            $auxClass = substr($include, 0, strpos($include, '.'));
            $auxInclude = substr($include, strpos($include, '.') + 1);

            $model = $this->returnTheCriteriaModel($auxClass);
            $transformer = $this->createTransformer($model);
            $newTransformer = new $transformer();

            return $this->includesAreCorrectInf($auxInclude, $newTransformer);
        } else {
            foreach ($includesAvailable as $aic) {
                if ($aic == $include) {
                    return true;
                }
            }    
        }
    }

    private function changeTableName($var)
    {
        if($var === 'assettypes') { return "asset_types"; }
        if($var === 'carrierdevices') { return "carrier_devices"; }
        if($var === 'carrierimages') { return "carrier_images"; }
        if($var === 'categoryappsapp') { return "categoryapps_app"; }
        if($var === 'categoryappsimage') { return "categoryapps_image"; }
        if($var === 'companyaddress') { return "company_address"; }
        if($var === 'companycurrentbillmonths') { return "company_current_bill_months"; }
        if($var === 'companydomains') { return "company_domains"; }
        if($var === 'companyrules') { return "company_rules"; }
        if($var === 'companysaml2') { return "company_saml2"; }
        if($var === 'conditionfields') { return "condition_fields"; }
        if($var === 'conditionoperators') { return "condition_operators"; }
        if($var === 'customrequests') { return "custom_requests"; }
        if($var === 'devicevariationimages') { return "deviceVariation_images"; }
        if($var === 'deviceimages') { return "device_images"; }
        if($var === 'devicemodifications') { return "device_modifications"; }
        if($var === 'devicetypes') { return "device_types"; }
        if($var === 'deviceusers') { return "device_users"; }
        if($var === 'devicevariations') { return "device_variations"; }
        if($var === 'devicevariations_modifications') { return "device_variations_modifications"; }
        if($var === 'emailnotifications') { return "email_notifications"; }
        if($var === 'employeeassets') { return "employee_assets"; }
        if($var === 'jobstatuses') { return "job_statuses"; }
        if($var === 'notificationgroups') { return "notification_groups"; }
        if($var === 'notificationscategoriesingroup') { return "notifications_categories_in_group"; }
        if($var === 'oauthaccesstokens') { return "oauth_access_tokens"; }
        if($var === 'oauthauthcodes') { return "oauth_auth_codes"; }
        if($var === 'oauthclients') { return "oauth_clients"; }
        if($var === 'oauthpersonal_access_clients') { return "oauth_personal_access_clients"; }
        if($var === 'oauthrefresh_tokens') { return "oauth_refresh_tokens"; }
        if($var === 'orderapps') { return "order_apps"; }
        if($var === 'orderdevicevariations') { return "order_device_variations"; }
        if($var === 'packageaddress') { return "package_address"; }
        if($var === 'packageapps') { return "package_apps"; }
        if($var === 'packagedevices') { return "package_devices"; }
        if($var === 'packageservices') { return "package_services"; }
        if($var === 'passwordreminders') { return "password_reminders"; }
        if($var === 'passwordresets') { return "password_resets"; }
        if($var === 'permissionrole') { return "permission_role"; }
        if($var === 'presetdevice_variations') { return "preset_device_variations"; }
        if($var === 'presetimages') { return "preset_images"; }
        if($var === 'roleuser') { return "role_user"; }
        if($var === 'scopepermission') { return "scope_permission"; }
        if($var === 'serviceitems') { return "service_items"; }
        if($var === 'syncjobs') { return "sync_jobs"; }
        if($var === 'systemrules') { return "system_rules"; }
        if($var === 'udlvaluepaths') { return "udl_value_paths"; }
        if($var === 'udlvaluepathscreatorsusers') { return "udl_value_paths_creators_users"; }
        if($var === 'udlvalues') { return "udl_values"; }
        if($var === 'useraddress') { return "user_address"; }
        if($var === 'userdevicevariations') { return "user_device_variations"; }
        if($var === 'usernotifications') { return "user_notifications"; }
        if($var === 'userservices') { return "user_services"; }
        if($var === 'userudls') { return "user_udls"; }

        return $var;
    }

    private function retrieveInformationInAnArray($array) {
        $arrayAux = [];
        foreach ($array as $key => $value) {
            if (strpos($value, '=')) {
                $relationship = substr($value, 0, strpos($value, '='));
                $aux['value'] = substr($value, strpos($value, '=') + 1);
                if (strpos($relationship, '][')) {
                    $relation = substr($relationship, 1, strpos($relationship, '][')-1);
                    $aux['operation'] = substr($relationship, strpos($relationship, '][') + 2 , -1);
                    if (strpos($relation, '.')) {

                        $parts = explode('.', $relation);
                        $aux['relColumn'] = $parts[count($parts) - 1];
                        $relKey = '';
                        $parts = array_slice($parts, 0, count($parts) - 1);
                        
                        foreach ($parts as $part) {
                            if ($relKey == '') {
                                $relKey = $part;   
                            } else {
                                $relKey = $relKey . '.' . $part;
                            }                        
                        }

                        $aux['relKey'] = $relKey;
                    } else {
                        $aux['relKey'] = '';
                        $aux['relColumn'] = $relation;
                    }
                } else if (strpos($relationship, '.')) {
                    $relationship = substr($relationship, 1, -1);

                    $aux['operation'] = 'eq';$parts = explode('.', $relationship);
                    $aux['relColumn'] = $parts[count($parts) - 1];
                    $relKey = '';
                    $parts = array_slice($parts, 0, count($parts) - 1);
                    
                    foreach ($parts as $part) {
                        if ($relKey == '') {
                            $relKey = $part;   
                        } else {
                            $relKey = $relKey . '.' . $part;
                        }                        
                    }

                    $aux['relKey'] = $relKey;
                } else {}
            } else {}
            array_push($arrayAux, $aux);
        }
        return $arrayAux;
    }

    private function arrayNeedsIncludes($array) {
        $ok = true;
        foreach ($array as $value) {
            if($value['relKey'] != '') {
                $ok = true;
            } else {
                $ok = false;
            }
        }

        return $ok;
    }

    /**
     * @param $query
     * @param $filterKey
     * @param $op
     * @param $val
     * @return mixed
     * @throws BadCriteriaException
     */
    protected function executeCriteria($query, $filterKey, $op, $val, $type)
    {
        switch ($op) {
            case 'gt':
                if ($type == 'OR') {
                    $query->orWhere($filterKey, '>', $val);
                } else {
                    $query->where($filterKey, '>', $val);
                }

                break;
            case 'lt':
                if ($type == 'OR') {
                    $query->orWhere($filterKey, '>', $val);
                } else {
                    $query->where($filterKey, '<', $val);
                }

                break;
            case 'ge':
            case 'gte':
                if ($type == 'OR') {
                    $query->orWhere($filterKey, '>=', $val);
                } else {
                    $query->where($filterKey, '>=', $val);
                }

                break;
            case 'lte':
            case 'le':
                if ($type == 'OR') {
                    $query->orWhere($filterKey, '<=', $val);
                } else {
                    $query->where($filterKey, '<=', $val);
                }

                break;
            case 'ne':
                // Handle delimited lists
                $vals = explode(',', $val);
                $vals = $this->extractAdvancedCriteria($vals);
                if (count($vals) === 0) {
                    continue;
                }
                if ($type == 'OR') {
                    $query->orWhereNotIn($filterKey, $vals);
                } else {
                    $query->whereNotIn($filterKey, $vals);
                }

                break;
            case 'eq':
                // Handle delimited lists
                $vals = explode(',', $val);
                $vals = $this->extractAdvancedCriteria($vals);
                if (count($vals) === 0) {
                    continue;
                }
                if ($type == 'OR') {
                    $query->orWhereIn($filterKey, $vals);
                } else {
                    $query->whereIn($filterKey, $vals);
                }

                break;
            case 'like':
                // Handle delimited lists
                $vals = explode(',', $val);
                $vals = $this->extractAdvancedCriteria($vals);
                if (count($vals) === 0) {
                    continue;
                }

                $queryFunction = function ($query) use ($vals, $filterKey) {
                    foreach ($vals as $v) {
                        $v = str_replace('*', '%', $v);
                        if (strpos($v, '%') === false) {
                            $v = '%' . $v . '%';
                        }
                        $query->orWhere($filterKey, 'LIKE', $v);
                    }
                };

                if ($type == 'OR') {
                    $query->orWhere($queryFunction);

                } else {
                    $query->where($queryFunction);

                }

                break;
            default:
                throw new BadCriteriaException('Invalid filter operator');
                break;
        }
        return $query;
    }

    /**
     * Apply sort criteria to the current query.
     *
     * @return $this
     *
     * @throws BadCriteriaException
     */
    protected function sort()
    {
        if ($this->isInclude === true) {
            return $this;
        }

        if ($this->sortCriteria === null) {
            return $this;
        }

        foreach ($this->sortCriteria->sorting() as $sortColumn => $direction) {
            if (in_array($sortColumn, $this->criteriaModelColumns)) {
                $this->criteriaQuery->orderBy($sortColumn, $direction);
            } else {
                throw new BadCriteriaException('Invalid sort criteria');
            }
        }

        return $this;
    }

    /**
     * @param $vals
     *
     * @return mixed
     */
    protected function extractAdvancedCriteria($vals)
    {
        // Ignore more complicated criteria, it's handled elsewhere
        foreach ($vals as $key => $val) {
            if (strpos($val, '[') === 0) {
                unset($vals[$key]);
            }
        }

        return $vals;
    }

    /**
     * @return mixed
     */
    public function getRequestCriteria()
    {
        $filters = $this->getFilters();
        $sort = $this->getSort();
        $fields = $this->getFields();

        $this->criteria['filters'] = $filters;
        $this->criteria['sort'] = $sort;
        $this->criteria['fields'] = $fields;

        return $this->criteria;
    }

    /**
     * @return Sorting
     */
    public function getSort()
    {
        $sort = new Sorting(\Request::get('sort', null));
        return $sort;
    }

    /**
     * @return Filters
     */
    public function getFilters()
    {
        $filters = new Filters((array)\Request::get('filter', null));
        return $filters;
    }


    /**
     * @return Fields
     */
    public function getFields()
    {
        $fields = new Fields(\Request::get('fields', null));
        return $fields;
    }

}

