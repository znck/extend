<?php namespace Znck\Extend;

use Illuminate\Database\Eloquent\Model;

abstract class ExtendedModel extends Model
{
    use Extendible;
}
