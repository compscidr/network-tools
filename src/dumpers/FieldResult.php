<?php
class FieldResult {
    public bool $isField;
    public string $fieldName;

    public function __construct(bool $isField, string $fieldName) {
        $this->isField = $isField;
        $this->fieldName = $fieldName;
    }
};
?>