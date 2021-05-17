<?php

namespace App\Http\Controllers;

class NewsletterUtilController extends NewsUtilController
{

    public function __construct(NewsletterController $res) {
        $this->resource = $res->resource;
        $this->resourceLabel = $res->resourceLabel;
        $this->user = $res->user;
    }
}