<?php

namespace JCLE\MemoBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use JCLE\MemoBundle\Entity\Icon;

class IdToSlugTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($icon)
    {
        if (null === $icon) {
            return "";
        }
        
        $slug = $this->om
            ->getRepository('JCLEMemoBundle:Icon')
            ->findOneBy(array('id' => $icon));

        return $slug->getSlug();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($slug)
    {
        if (!$slug) {
            return null;
        }

        $icon = $this->om
            ->getRepository('JCLEMemoBundle:Icon')
            ->findOneBy(array('slug' => $slug))
        ;

        if (null === $icon) {
            throw new TransformationFailedException(sprintf(
                'Le problème avec le numéro "%s" ne peut pas être trouvé!',
                $slug
            ));
        }

        return $icon;
    }
}