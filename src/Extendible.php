<?php namespace Znck\Extend;

trait Extendible
{
    protected $extendColumn = 'additional';

    protected $extends = [];

    // -- Overrides.
    protected function getArrayableAttributes()
    {
        return parent::getArrayableAttributes() + $this->getArrayableExtends();
    }

    public function getAttributeFromArray($key)
    {
        if ($this->isExtendedAttribute($key)) {
            return $this->getExtendedAttribute($key);
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);

        return $this->setExtendedAttribute($key, $value);
    }

    public function getArrayableExtends()
    {
        if (! count($this->extends)) {
            return [];
        }

        return $this->getArrayableItems(
            array_combine($this->appends, $this->appends)
        );
    }

    public function getArrayableExtendValues()
    {
        $attributes = [];

        foreach ($this->getArrayableExtends() as $key) {
            $attributes[$key] = $this->getAttribute($key);
        }
    }

    public function getExtendedAttribute($key)
    {
        $attributes = $this->{$this->extendColumn};

        if (array_key_exists($key, $attributes)) {
            return $attributes[$key];
        }
    }

    public function setExtendedAttribute($key, $value)
    {
        if ($this->isExtendedAttribute($key)) {
            unset($this->attributes[$key]);

            $this->{$this->extendColumn}[$key] = $value;

            return $this;
        }

        return $this;
    }

    public function isExtendedAttribute($key)
    {
        return in_array($key, $this->appends);
    }
}
