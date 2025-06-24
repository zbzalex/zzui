<?php

namespace miru\presentation\pages\forum;

use zzui\Context;
use zzui\markup\html\Label;
use zzui\markup\html\ListAdapter;
use zzui\markup\html\Page;
use zzui\markup\html\PageLink;
use zzui\markup\MarkupContainer;

class ForumPage extends Page
{
  public function __construct(Context $ctx, array $params = [])
  {
    parent::__construct($ctx);

    $items = [
      [
        'id' => 1,
        'name' => 'Новости',
      ],
      [
        'id' => 2,
        'name' => 'Справочник',
      ],
      [
        'id' => 3,
        'name' => 'Старая таверна',
      ],
    ];

    $forumListAdapter = new ListAdapter(
      "forum_list",
      $items,
      function (
        $item,
        MarkupContainer $itemMarkupContainer
      ) {
        $itemMarkupContainer->add(
          new PageLink("link", ForumPage::class, [
            'id' => $item['id'],
          ])
        );
        $itemMarkupContainer->add(new Label("name", $item['name']));
        $itemMarkupContainer->add(new Label("theme_count", 0));
        $itemMarkupContainer->add(new Label("post_count", 0));
      }
    );

    $this->add($forumListAdapter);
  }
}
