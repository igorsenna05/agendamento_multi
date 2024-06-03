<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionTree extends Model
{
    use HasFactory;
    protected $table = 'decision_tree';

    protected $fillable = [
        'label',
        'parent_id',
        'action_type',
        'action_value',
        'instruction_id',
        'position'
    ];

    public function parent()
    {
        return $this->belongsTo(DecisionTree::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DecisionTree::class, 'parent_id');
    }

    public function instruction()
    {
        return $this->belongsTo(Instruction::class);
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
