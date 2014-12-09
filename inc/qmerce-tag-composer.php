<?php

class QmerceTagComposer {

    /**
     * composes interaction tag with given id.
     * @param $interactionId
     * @return string
     */
    public function composeInteractionTag($interactionId)
    {
        return '<interaction id="' . $interactionId . '"></interaction>';
    }

    /**
     * composes automation tag (with userId taken by token)
     * @return string
     */
    public function composeAutomationTag()
    {
        return '<interaction data-random="' . $this->getUserId() . '"></interaction>';
    }

    /**
     * Returns the user ID according to the given Qmerce Token (in settings panel)
     * @return mixed|void
     */
    private function getUserId()
    {
        $userId = get_option( 'qmerce-user-id' );

        if (!$userId) {
            $userId = $this->fetchUserId($this->getAuthToken());
            add_option( 'qmerce-user-id', $userId );
        }

        return $userId;
    }

    /**
     * Returns the publisher token from the DB.
     * @return string
     */
    private function getAuthToken() {
        $qmerceSettings = get_option( 'qmerce-settings-admin' );

        return $qmerceSettings['auth_token'];
    }

    /**
     * Fetches the user ID from the Qmerce's user service and returns the user ID.
     * @param string $token
     * @return string
     */
    private function fetchUserId($token) {
        $response = json_decode(file_get_contents(QMERCE_USER_SERVICE . '/user?authToken=' . $token));

        if (!$response->code == 200 || !$response->payload) {
            return '';
        }

        return $response->payload->userId;
    }
}