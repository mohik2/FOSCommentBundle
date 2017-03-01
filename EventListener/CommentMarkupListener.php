<?php

/*
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace FOS\CommentBundle\EventListener;

use FOS\CommentBundle\Event\CommentEvent;
use FOS\CommentBundle\Events;
use FOS\CommentBundle\Markup\ParserInterface;
use FOS\CommentBundle\Model\RawCommentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Parses a comment for markup and sets the result
 * into the rawBody property.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class CommentMarkupListener implements EventSubscriberInterface
{
    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * Constructor.
     *
     * @param \FOS\CommentBundle\Markup\ParserInterface $parser
     */
    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parses raw comment data and assigns it to the rawBody
     * property.
     *
     * @param \FOS\CommentBundle\Event\CommentEvent $event
     */
    public function markup(CommentEvent $event)
    {
        $comment = $event->getComment();

        if (!$comment instanceof RawCommentInterface) {
            return;
        }

        $result = $this->parser->parse($comment->getBody());
        $comment->setRawBody($result);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(Events::COMMENT_PRE_PERSIST => 'markup');
    }
}
