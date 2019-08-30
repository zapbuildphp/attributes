<?php

declare(strict_types=1);

namespace Rinvex\Attributes\Events;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Entity;

class EntityWasDeleted
{
    /**
     * Handle the entity deletion.
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     *
     * @return void
     */
    public function handle(Entity $entity)
    {
         
        // We will initially check if the model is using soft deletes. If so,
        // the attribute values will remain untouched as they should sill
        // be available till the entity is truly deleted from database.
        if (in_array(SoftDeletes::class, class_uses_recursive(get_class($entity))) && ! $entity->isForceDeleting()) {
            return;
        }

        foreach ($entity->getEntityAttributes() as $attribute) {
            if ($entity->relationLoaded($relation = $attribute->getAttribute('slug'))
                && ($values = $entity->getRelationValue($relation)) && ! $values->isEmpty()) {

               $attribute->getAttribute('type')::whereIn('attribute_id',$values->pluck('attribute_id')->toArray())
                                                ->whereIn('entity_id',$values->pluck('entity_id')->toArray())
                                                ->whereIn('entity_type',$values->pluck('entity_type')->toArray())
                                                ->delete();
                // Calling the `destroy` method from the given $type model class name
                // will finally delete the records from database if any was found.
                // We'll just provide an array containing the ids to be deleted.
                //forward_static_call_array([$attribute->getAttribute('type'), 'destroy'], [$values->pluck('id')->toArray()]);
            }
            
        }      
    }
}
