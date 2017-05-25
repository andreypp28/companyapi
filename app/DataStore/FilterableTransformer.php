<?php

namespace WA\DataStore;

use Illuminate\Support\Str;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\TransformerAbstract;
use WA\Helpers\Traits\Criteria;

abstract class FilterableTransformer extends TransformerAbstract
{
    use Criteria;

    /**
     * Array of includes that should return empty result-sets.  The default behavior is to NOT include results where
     * the child query (include) has 0 results.  Override the default by putting the include name here.
     *
     * @var array
     */
    protected $emptyResults = [

    ];

    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'include')) {
            return $this->dynamicInclude($method, $parameters);
        }
        $className = static::class;
        throw new \BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }

    public function dynamicInclude($method, $parameters)
    {
        $this->criteria = $this->getRequestCriteria();
        $finder = strtolower(substr($method, 7));
        $resource = $parameters[0];
        $returnEmptyResults = false;

        $transformer = $this->createTransformer($finder);
        
        if (!class_exists($transformer)) {
            throw new \BadMethodCallException("Unable to create $transformer");
        }

        if (in_array($finder,$this->emptyResults)) {
            $returnEmptyResults = true;
        }

        /*
         *  @TODO: @adosaiguas: we have to discuss if we need to retrieve all the includes information or only the includes that 
         *     accomplishes the filters and how to tell the API which option. For now, we will retrieve all of them!
         *
         *  File@Function (line) - Modified? (Yes/No)
         *
         *  UserTransformer@includeAllocations (82) - Modified? (No)
         *  ServiceItemTransformer@includeOrders (37) - Modified? (Yes)
         *  ServiceTransformer@includeServiceitems (51) - Modified? (Yes)
         *  OrderTransformer@includeServiceitems (50) - Modified? (No)
         *  OrderTransformer@includeApps (60) - Modified? (No)
         *  OrderTransformer@includeUsers (69) - Modified? (No)
         *  OrderTransformer@includePackages (78) - Modified? (No)
         *  OrderTransformer@includeDeviceVariations (87) - Modified? (No)
         *  OrderTransformer@includeServices (96) - Modified? (No)
         *  DeviceVariationTransformer@includeAllocations (52) - Modified? (No)
         *  DeviceTransformer@includeDevicetypes (55) - Modified? (Yes)
         *  CompanyTransformer@includeAllocations (59) - Modified? (No)
         *  AppTransformer@includeOrders (39) - Modified? (No)
         *  AppTransformer@includePackages (48) - Modified? (No)
         *
         */
        $criteria = null; //$this->criteria;

        $include = $this->applyCriteria($resource->$finder(), $criteria, true, null, $returnEmptyResults);
        return new ResourceCollection($include->get(), new $transformer(), $finder);

    }

    private function createTransformer($finder) 
    {
        if($finder === 'devicevariations') {
            return "\\WA\\DataStore\\DeviceVariation\\DeviceVariationTransformer";
        }

        if($finder === 'devicetypes') {
            return "\\WA\\DataStore\\DeviceType\\DeviceTypeTransformer";
        }

        if($finder === 'udlvalues') {
            return "\\WA\\DataStore\\UdlValue\\UdlValueTransformer";
        }

        $model = title_case(str_singular($finder));
        return "\\WA\\DataStore\\${model}\\${model}Transformer";
    }
}
