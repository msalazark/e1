<?php

class SimpleTemplateEngine {

	private static function parseToken ($str) {
		$path = explode('.', $str);
		$token = '';

		foreach ($path as $i => $k) {
			$token .= $i ? sprintf('[%s]', ctype_digit($k) ? $k : "'{$k}'") : '$'.$k;
		}

		return $token;
	}


	private static function parseEcho ($str) {
		$raw = strpos($str, '|r');

		if ($raw) {
			$str = substr_replace($str, '', -2);
		}

		$token = self::parseToken($str);

		return $raw ? "<?={$token}?>" : "<?=htmlspecialchars({$token})?>";
	}


	private static function parseForeach ($str) {
		if (preg_match('/^foreach ([\w\.]+) as (\w+)$/i', $str, $m)) {
			return sprintf(
				'<?php foreach (%s as %s) { ?>',
				self::parseToken($m[1]),
				'$'.$m[2]
			);

		} else if (preg_match('/^foreach ([\w\.]+) as (\w+) => (\w+)$/i', $str, $m)) {
			return sprintf(
				'<?php foreach (%s as %s => %s) { ?>',
				self::parseToken($m[1]),
				'$'.$m[2],
				'$'.$m[3]
			);

		} else {
			return '';
		}
	}


	private static function parseIf ($str) {
		if (preg_match('/^if ([\w\.]+)$/i', $str, $m)) {
			$token = self::parseToken($m[1]);
			return sprintf('<?php if (isset(%s) && %s) { ?>', $token, $token);

		} else {
			return '';
		}
	}


	private static function parse ($str) {
		return preg_replace_callback('/\{\{([^}]+)\}\}/', function ($m) {
			$stm   = trim($m[1]);
			$parts = explode(' ', $stm);

			if (count($parts) > 1) {
				switch ($parts[0]) {
					case 'foreach': return self::parseForeach($stm);
					case 'if'     : return self::parseif($stm);
					default       : return '';
				}

			} else if (preg_match(':^/(foreach|if)$:i', $stm)) {
				return '<?php } ?>';

			} else {
				return self::parseEcho($stm);
			}
		}, $str);
	}


	public static function print ($str, $data = []) {
		$parsed = self::parse($str);
		$has_php = strpos($parsed, '<?=') !== false || strpos($parsed, '<?php') !== false;

		if ($has_php) {
			extract($data, EXTR_REFS);
			$tmp_file = tempnam(JPATH_SITE . '/tmp', 'html');
			file_put_contents($tmp_file, $parsed);
			require_once $tmp_file;
			unlink($tmp_file);
		} else {
			echo $parsed;
		}
	}

}
