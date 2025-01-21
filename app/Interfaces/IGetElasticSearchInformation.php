<?php

namespace App\Interfaces;

interface IGetElasticSearchInformation
{
    public function getElasticSearchIndex();
    public function getElasticSearchType();
    public function getElasticSearchableFields();

}
