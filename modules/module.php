<?php
/*
 * MAIN MODULE CLASS
 * All other modules should be extended from this on.
 * This module gives a validate request function to check for request vars
 * and also has a basic json ouput function. see below.
 */
class module {
    /**
     * Check that all the @fields were sent on the request
     * returns true/false.
     *
     * @fields has to be an array of strings
     */
    public function validateRequest($fields, $return = false) {
        // If @fields ain't an array return false and exit
        if (!is_array($fields)) {
            return false;
        }

        foreach ($fields as $field) {
            if (!isset($_REQUEST[$field])) {
                // If we specified that the function must return do so
                if ($return) {
                    return false;
                } else { // If not, send the default reponse and exit
                    $this->respond(false, "Not all params supplied.");
                }
            }
        }
    }

    /**
     * Sends a json encoded response back to the caller
     * with @succeeded and @message
     */
    public function respond($succeeded, $message, $params = null) {
        $response = array(
            'succeeded' => $succeeded,
            'message' => $message,
            'params' => $params
        );
        echo json_encode($response);
        exit(0);
    }

}

?>