<?php

require __DIR__ . '/../../../configuration.php';
require __DIR__ . '/helper.php';


try {
	$pdo = EsanContactFormPDO::build(new JConfig);
	$ecf = new EsanContactForm($pdo);
	$ecf->proccess();

	EsanContactFormResponse::sendJson(200, ['success' => true]);

} catch (EsanContactFormException $e) {
	EsanContactFormResponse::sendJson($e->getHttpStatusCode(), ['error' => $e->getMessage()]);

} catch (Exception $e) {
	EsanContactFormResponse::sendJson(500, ['error' => $e->getMessage()]);
}
