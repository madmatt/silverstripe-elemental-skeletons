<?php

namespace DNADesign\ElementalSkeletons\Extensions;

use DNADesign\Elemental\Extensions\ElementalAreasExtension;
use DNADesign\ElementalSkeletons\Models\Skeleton;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;

class CMSPageAddControllerExtension extends Extension
{
    public function updatePageOptions(FieldList $fields)
    {
        $skeletons = ['' => 'Select skeleton'] + Skeleton::get()->map('ID', 'Title')->toArray();

        $title = '<span class="step-label"><span class="flyout">Step 3. </span><span class="title">Select skeleton to create page with</span></span>';
        $skeletonField = new DropdownField('SkeletonID', DBField::create_field('HTMLFragment', $title), $skeletons);
        $fields->insertAfter('PageType', $skeletonField);
    }

    public function updateDoAdd(DataObject $record, Form $form)
    {
        // Ensure the newly created record has the elemental extension
        if (!$record->hasExtension(ElementalAreasExtension::class)) {
            return;
        }

        // We have to write the record before we can add has_many relations
        $record->write();

        // Find and verify the Skeleton is valid
        $skeletonId = (int)$form->Fields()->dataFieldByName('SkeletonID')->Value();
        if ($skeletonId && $skeletonId > 0) {
            /** @var Skeleton $skeleton */
            $skeleton = Skeleton::get()->byID($skeletonId);

            if ($skeleton && $skeleton->Parts()) {
                foreach ($skeleton->Parts() as $part) {
                    // Load each skeleton part as a new element and assign it to the newly created page
                    $elementClass = $part->ElementType;
                    $element = new $elementClass;
                    $record->ElementalArea()->Elements()->add($element);
                }
            }
        }
    }
}