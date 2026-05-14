<?php

test('home page loads', function () {
    $this->get(route('home'))->assertOk();
});

test('features page loads', function () {
    $this->get(route('features'))->assertOk();
});

test('pricing page loads', function () {
    $this->get(route('pricing'))->assertOk();
});

test('vs brightplans page loads', function () {
    $this->get(route('vs-brightplans'))->assertOk();
});

test('about page loads', function () {
    $this->get(route('about'))->assertOk();
});

test('contact page loads', function () {
    $this->get(route('contact'))->assertOk();
});

test('privacy page loads', function () {
    $this->get(route('privacy'))->assertOk();
});

test('terms page loads', function () {
    $this->get(route('terms'))->assertOk();
});

test('blog page loads', function () {
    $this->get(route('blog'))->assertOk();
});

test('demo page loads', function () {
    $this->get(route('demo.show'))->assertOk();
});
