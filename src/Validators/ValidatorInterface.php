<?php

namespace VCComponent\Laravel\Vicoders\Core\Validators;

interface ValidatorInterface
{
    const RULE_ADMIN_CREATE      = 'RULE_ADMIN_CREATE';
    const RULE_ADMIN_UPDATE      = 'RULE_ADMIN_UPDATE';
    const RULE_CREATE            = 'RULE_CREATE';
    const RULE_UPDATE            = 'RULE_UPDATE';
    const RULE_LIST              = 'RULE_LIST';
    const BULK_UPDATE_STATUS     = 'BULK_UPDATE_STATUS';
    const UPDATE_STATUS_ITEM     = 'UPDATE_STATUS_ITEM';
    const CREATE_COMMENT         = 'CREATE_COMMENT';
    const CREATE_RATING          = 'CREATE_RATING';
    const RULE_ADMIN_UPDATE_DATE = 'RULE_ADMIN_UPDATE_DATE';

    public function isValid($data, $action);
}
