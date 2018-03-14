<?php

declare(strict_types=1);

namespace Medz\Cors;

interface CorsInterface
{
    /**
     * Run the CORS handle.
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle();

    /**
     * Set a request.
     *
     * @param string $type
     * @param any    $request set a request, if native PHP set the "$request" null.
     *                        the "$request" MUST be implemented.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setRequest(string $type, $request);

    /**
     * Set a response.
     *
     * @param string $type
     * @param any    $response the "$response" is framework interface or array.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setResponse(string $type, $response);

    /**
     * Get set CORS response.
     *
     * @return any The return to the solution depends on the set of response.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getResponse();
}
