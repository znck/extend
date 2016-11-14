<?php namespace Znck\Extend;

trait Extendible
{
    protected static $extendColumn = 'additional';

    protected $extends = [];

    public function getCasts() {
        return [self::$extendColumn => 'array'] + parent::getCasts();
    }

    // -- Overrides.
    protected function getArrayableAttributes() {
        return parent::getArrayableAttributes() + $this->getArrayableExtends();
    }

    public function getAttribute($key) {
        if ($this->isExtendedAttribute($key)) {
            return $this->getExtendedAttribute($key);
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value) {
        parent::setAttribute($key, $value);

        return $this->setExtendedAttribute($key, $value);
    }

    public function getArrayableExtends() {
        if (!count($this->extends)) {
            return [];
        }

        return $this->getArrayableItems(
            array_combine($this->extends, $this->extends)
        );
    }

    public function getArrayableExtendValues() {
        $attributes = [];

        foreach ($this->getArrayableExtends() as $key) {
            $attributes[$key] = $this->getAttribute($key);
        }
    }

    public function getExtendedAttribute($key) {
        $attributes = (array)$this->{self::$extendColumn};

        if (array_key_exists($key, $attributes)) {
            return $attributes[$key];
        }
    }

    public function setExtendedAttribute($key, $value) {
        if ($this->isExtendedAttribute($key)) {
            unset($this->attributes[$key]);
            $this->{self::$extendColumn} = array_merge([$key => $value], (array)$this->{self::$extendColumn});

            return $this;
        }

        return $this;
    }

    public function isExtendedAttribute($key) {
        return in_array($key, $this->extends);
    }

    public static function getExtendedQueryKey($key) {
        return self::$extendColumn.'->'.$key;
    }
}
