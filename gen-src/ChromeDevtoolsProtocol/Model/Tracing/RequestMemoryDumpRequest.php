<?php

namespace ChromeDevtoolsProtocol\Model\Tracing;

/**
 * Request for Tracing.requestMemoryDump command.
 *
 * @generated This file has been auto-generated, do not edit.
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
final class RequestMemoryDumpRequest implements \JsonSerializable
{
	/**
	 * Enables more deterministic results by forcing garbage collection
	 *
	 * @var bool|null
	 */
	public $deterministic;


	public static function fromJson($data)
	{
		$instance = new static();
		if (isset($data->deterministic)) {
			$instance->deterministic = (bool)$data->deterministic;
		}
		return $instance;
	}


	public function jsonSerialize()
	{
		$data = new \stdClass();
		if ($this->deterministic !== null) {
			$data->deterministic = $this->deterministic;
		}
		return $data;
	}


	/**
	 * Create new instance using builder.
	 *
	 * @return RequestMemoryDumpRequestBuilder
	 */
	public static function builder(): RequestMemoryDumpRequestBuilder
	{
		return new RequestMemoryDumpRequestBuilder();
	}


	/**
	 * Create new empty instance.
	 *
	 * @return self
	 */
	public static function make(): self
	{
		return static::builder()->build();
	}
}