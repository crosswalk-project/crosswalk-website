# Test Suite

The Crosswalk Project is an HTML application runtime, built on open source foundations. The Test Suite for Crosswalk Project, a collection of test cases that are intended to be used to test Crosswalk Project to show it's specified set of behaviours is also open source.

The souce code can be obtained from [Crosswalk Test Suite repository](https://github.com/crosswalk-project/crosswalk-test-suite).

## Overview

There are several components in Crosswalk Project, for instance, the Web APIs stands for the APIs can be used by web applications running on the Crosswalk Project runtime, and the Embedding API stands for the Java API for embedding the Crosswalk Project in an Android application. The corresponding test suites for these components are well orginazed with following parts:

* Web API tests
* Embedding API tests
* Web Runtime and feature tests
* Crosswalk based Cordova tests
* Usecases

Tools, documentations and some other tests e.g. stability tests and BAT tests are also included in the test suite. Please refer to [test suite wiki](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-test-suite) for more details.

## Development guide

Crosswalk Test Suite provides a series of [development guides](https://github.com/crosswalk-project/crosswalk-test-suite/tree/master/doc) for test cases developers and contributors, which covers test suite source layout, coding style, test case naming convention, folder naming convention, test case classification as well as how to add test cases into test suites for nearly all the kinds of components like Web API, Embedding API, Cordova and Web Runtime. You may also need to understand the [test case priority and test coverage](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-test-suite) before developing the test cases.

## Branches

Crosswalk Test Suite not only covers master branch but also beta branch since usually case bugs are affected on both of them, sometimes we need to add hotfixes on beta branch. Crosswalk Test Suite branch schedule aligns with [branch dates](https://github.com/crosswalk-project/crosswalk-website/wiki/Release-dates) of Crosswalk Project which follows [Crosswalk release methodology](https://github.com/crosswalk-project/crosswalk-website/wiki/Release-methodology).

## Contribute test cases

No test is too small or too simple, everyone is welcome and even encouraged to contribute to test development, please refer to detailed contribution steps in [contribute tests](https://crosswalk-project.org/contribute/contributing-tests.html) page.

