# Policy Evaluator

[![License](https://poser.pugx.org/tomzx/policy-evaluator/license.svg)](https://packagist.org/packages/tomzx/policy-evaluator)
[![Latest Stable Version](https://poser.pugx.org/tomzx/policy-evaluator/v/stable.svg)](https://packagist.org/packages/tomzx/policy-evaluator)
[![Latest Unstable Version](https://poser.pugx.org/tomzx/policy-evaluator/v/unstable.svg)](https://packagist.org/packages/tomzx/policy-evaluator)
[![Build Status](https://img.shields.io/travis/tomzx/policy-evaluator.svg)](https://travis-ci.org/tomzx/policy-evaluator)
[![Code Quality](https://img.shields.io/scrutinizer/g/tomzx/policy-evaluator.svg)](https://scrutinizer-ci.com/g/tomzx/policy-evaluator/code-structure)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/tomzx/policy-evaluator.svg)](https://scrutinizer-ci.com/g/tomzx/policy-evaluator)
[![Total Downloads](https://img.shields.io/packagist/dt/tomzx/policy-evaluator.svg)](https://packagist.org/packages/tomzx/policy-evaluator)

`Policy Evaluator` is a simple system based on AWS Policies. Given a set of statements, `Policy Evaluator` will then be able to answers to queries about whether this set of policies is allowed (or not) to perform a given action on a given resource.

## Getting started
`php composer.phar require tomzx/policy-evaluator`

## Example
```php
use tomzx\PolicyEvaluator\Evaluator;

$evaluator = new Evaluator([
	'Statement' => [
		[
			'Action' => 'service:*',
			'Resource' => 'arn:aws:*',
			'Effect' => 'Allow',
		],
		[
			'Action' => 's3:*',
			'Resource' => 'arn:aws:s3:::my-bucket/*',
			'Effect' => 'Allow',
		],
	],
]);

$evaluator->canExecuteActionOnResource('service:test', 'arn:aws:test');
$evaluator->canExecuteActionOnResource('s3:GetObject', 'arn:aws:s3:::my-bucket/some-file');
```

## License

The code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). See [LICENSE](LICENSE).
