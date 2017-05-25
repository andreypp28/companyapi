<?php

namespace WA\Services\Form;

/**
 * Class AbstractForm.
 */
class AbstractForm
{
    /**
     * Errors generated form forms.
     *
     * @var
     */
    protected $errors;

    /**
     * @var \WA\Services\Validation\ValidableInterface
     */
    protected $validator;

    /**
     * Company's context that we are currently working within.
     *
     * @var
     */
    protected $currentCompany;

    /**
     * @param array $data
     *
     * @return bool
     */
    protected function valid(array $data)
    {
        return $this->validator->with($data)->passes();
    }

    /**
     * Helper to set notification to a container.
     *
     * @param string $type      {error | info | success}
     * @param string $msg
     * @param string $container
     */

    /**
     * @return mixed
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @param $type
     * @param $msg
     */
    public function notify($type, $msg)
    {
        return $msg;
    }
}
