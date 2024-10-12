<?php

declare(strict_types=1);

namespace Novara\Psr7\Factory;

use Novara\Base\Novara;
use Novara\Psr7\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return Novara::Call::pass(
            Novara::Call::args(
                [
                    'code',
                    'reasonPhrase',
                ],
                func_num_args() >= 2
                    ? func_num_args()
                    : (
                        func_num_args() === 1
                            ?
                                [
                                    func_get_arg(0),
                                    '',
                                ]
                            :
                                [
                                    200,
                                    '',
                                ]
                    ),
            ),
            fn () => (new class extends Response {})
                ->withStatus(
                    func_get_arg(0)->code,
                    func_get_arg(0)->reasonPhrase,
                ),
        );
    }
}
