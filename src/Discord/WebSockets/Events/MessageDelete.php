<?php

/*
 * This file is apart of the DiscordPHP project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace Discord\WebSockets\Events;

use Discord\WebSockets\Event;

/**
 * Event that is emitted wheh `MESSAGE_DELETE` is fired.
 */
class MessageDelete extends Event
{
    /**
     * {@inheritdoc}
     *
     * @return Message The parsed data.
     */
    public function getData($data, $discord)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function updateDiscordInstance($data, $discord)
    {
        foreach ($discord->guilds as $index => $guild) {
            foreach ($guild->channels as $cindex => $channel) {
                if ($channel->id == $data->channel_id) {
                    foreach ($channel->messages as $mindex => $message) {
                        if ($message->id == $data->id) {
                            $channel->messages->pull($mindex);

                            break;
                        }
                    }

                    $guild->channels->pull($cindex);
                    $guild->channels->push($channel);

                    break;
                }
            }

            $discord->guilds->pull($index);
            $discord->guilds->push($guild);
        }

        return $discord;
    }
}
