<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/8/14
	 * Time: 7:41 PM
	 */

	namespace Utilities;


	class Utilities
	{
		/**
		 * This overeager function checks if a variable is set, empty, null-string, or just plain null in that order.
		 * If any argument meets the above requirements, the respective error is echoed out.
		 *
		 * @param array $vars The variables to check. It must be an array of variables, even if the variable itself is an array
		 * @param array $errors The respective errors to echo for each variable
		 *
		 * @param string $callback The callback to execute if there is an error
		 *
		 * @return bool True if all passed, false if not
		 */
		public function checkIsSet($vars, $errors, $callback = null) {
			$returnVal = true;
			for ($i = 0; $i < count($vars); $i++) {
				if (!isset($vars[$i]) || empty($vars[$i]) || $vars[$i] == "" || $vars[$i] == null) {
					$returnVal = false;
					echo $errors[$i];
				}
			}

			if (!$returnVal && !is_null($callback)) {
				$callback();
			}

			return $returnVal;
		}
	}