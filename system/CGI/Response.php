<?php

namespace Dea\CGI;

interface Response {
	public function output();
    public function content();
}