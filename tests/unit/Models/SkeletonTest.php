<?php

namespace DNADesign\ElementalSkeletons\Tests\Models;

use DNADesign\Elemental\Extensions\ElementalAreasExtension;
use DNADesign\Elemental\Models\ElementalArea;
use DNADesign\ElementalSkeletons\Models\Skeleton;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\SapphireTest;

class SkeletonTest extends SapphireTest
{
    protected static $required_extensions = [
        SiteTree::class => [
            ElementalAreasExtension::class
        ]
    ];

    public function testGetDecoratedBy()
    {
        $skeleton = new Skeleton();

        $classes = $skeleton->getDecoratedBy(ElementalAreasExtension::class, SiteTree::class);
    }
}