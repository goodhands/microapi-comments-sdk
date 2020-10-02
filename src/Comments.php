<?php

/**
 * This file is part of the goodhands/comments-sdk library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Samuel Olaegbe <olaegbesamuel@gmail.com>
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Goodhands\Comments;

use Goodhands\Comments\Exceptions\CommentsException;
use GuzzleHttp\Exception\GuzzleException;
use Goodhands\Comments\HTTP\Http;

/**
 * A simple PHP library to use the comments microservice at https://comments.microapi.dev
 */
class Comments
{
    public const BASE_URL = "https://comment.microapi.dev/v1/";

    private Http $http;

    public function __construct($ACCESS_TOKEN)
    {
        $this->http = new Http([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Authorization' => 'Bearer ' . $ACCESS_TOKEN,
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * Create a new comment resource
     * @param array $payload
     * @return Http
     * @throws GuzzleException
     */
    public function create($payload)
    {
        if (!$payload['refId']) {
            throw new CommentsException("Required arguments for payload are refId");
        }

        if (!$payload['ownerId']) {
            throw new CommentsException("Required arguments for payload are ownerId");
        }

        if (!$payload['content']) {
            throw new CommentsException("Required arguments for payload are content");
        }

        if (!$payload['origin']) {
            throw new CommentsException("Required arguments for payload are origin");
        }

        try {
            $this->http->post('comments', [
                "body" => json_encode($payload)
            ]);

            return $this->http;
        } catch (GuzzleException $ge) {
            throw $ge;
        }
    }

    /**
     * Find a single comment with the commentId
     * @param string $commentId
     * @return Http
     * @throws GuzzleException
     */
    public function find($commentId)
    {
        $this->http->get('comments/' . $commentId);

        return $this->http;
    }

    /**
     * Update a comment's content
     * @param string $commentId
     * @param array<string, string> $payload
     * @throws GuzzleException
     * @return Http
     */
    public function update(string $commentId, array $payload)
    {
        $this->http->patch('comments/' . $commentId, $payload);

        return $this->http;
    }

    /***
     * Delete a comment
     * @param string $commentId
     * @return Http
     * @throws GuzzleException
     */
    public function delete(string $commentId)
    {
        $this->http->delete('comments/' . $commentId);

        return $this->http;
    }

    /**
     * Update the flag status of a comment
     * @param string $commentId
     * @param array $payload
     * @return Http
     * @throws GuzzleException
     */
    public function flag(string $commentId, array $payload)
    {
        $this->http->patch('comments/' . $commentId . '/flag', $payload);

        return $this->http;
    }
}
