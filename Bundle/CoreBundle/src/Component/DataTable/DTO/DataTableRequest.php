<?php

namespace Umbrella\CoreBundle\Component\DataTable\DTO;

use Symfony\Component\HttpFoundation\Request;

class DataTableRequest
{
    protected Request $httpRequest;

    protected DataTable $dataTable;

    protected bool $isCallback;

    protected array $formData = [];

    public function __construct(Request $httpRequest, DataTable $dataTable, bool $validate = true)
    {
        $this->httpRequest = $httpRequest;
        $this->dataTable = $dataTable;

        if ($validate) {
            $this->isCallback = $httpRequest->isXmlHttpRequest()
                && $httpRequest->isMethod('GET')
                && $httpRequest->query->has('_dtid')
                && $httpRequest->query->get('_dtid') == $dataTable->getId();
        } else {
            $this->isCallback = false;
        }
    }

    public function getDataTable(): DataTable
    {
        return $this->dataTable;
    }

    public function getHttpRequest(): Request
    {
        return $this->httpRequest;
    }

    public function isCallback(): bool
    {
        return $this->isCallback;
    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    public function setFormData(array $formData)
    {
        $this->formData = $formData;
    }

    /**
     * @return mixed|null
     */
    public function getDraw()
    {
        return $this->httpRequest->query->get('draw');
    }

    public function getData(): array
    {
        return $this->httpRequest->query->all();
    }
}
