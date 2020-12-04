<?php

namespace VCComponent\Laravel\Vicoders\Core\Validators;

use VCComponent\Laravel\Vicoders\Core\Contracts\FileValidatorInterface;
use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class FileValidator extends AbstractValidator implements FileValidatorInterface
{

    protected $rules = [
        'RULE_CREATE' => [
            'file'        => ['required', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx'],
            'upload_path' => ['required', 'regex:/[a-z]*/'],
        ],
    ];
}
