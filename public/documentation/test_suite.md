# Test Suite

The Crosswalk Project test suite includes test cases that validate the behavior of the project. The tests are open source.  The souce code can be obtained from [Crosswalk Test Suite repository](https://github.com/crosswalk-project/crosswalk-test-suite).

## Overview

There are several components in the Crosswalk Project.  For example:

* Web API -- APIs used by web applications running on the Crosswalk Project runtime
* Embedding API --  the Java API for embedding the Crosswalk Project in an Android application.

The corresponding test suites for these components are well orginazed with the following parts:

* Web API tests
* Embedding API tests
* Web Runtime and feature tests
* Crosswalk based Cordova tests
* Usecases

Tools, documentation, and some other tests (e.g. stability tests and BAT tests) are also included in the test suite. Please refer to [test suite wiki](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-test-suite) for more details.

## Development guide

The Test Suite provides a series of [development guides](https://github.com/crosswalk-project/crosswalk-test-suite/tree/master/doc) for test case developers and contributors. The guides cover test suite source layout, coding style, test case naming convention, folder naming convention, test case classification, and how to add test cases into test suites for components like Web API, Embedding API, Cordova and Web Runtime. You may also need to understand the [test case priority and test coverage](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-test-suite) before developing the test cases.

## Branches

The Test Suite not only covers the master branch but also the beta branch since usually case bugs are affected on both of them and sometimes we need to add hotfixes on the beta branch. The Test Suite branch schedule aligns with [branch dates](https://github.com/crosswalk-project/crosswalk-website/wiki/Release-dates) of Crosswalk Project which follows [Crosswalk release methodology](https://github.com/crosswalk-project/crosswalk-website/wiki/Release-methodology).

## Contribute test cases

No test is too small or too simple if it helps to validate a feature or behavior. Everyone is encouraged to contribute to test development. Please refer to detailed contribution steps in [contribute tests](/contribute/contributing_tests.html) page.


