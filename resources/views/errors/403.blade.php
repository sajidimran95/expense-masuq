@extends('errors.layout')

@section('title', 'Access Denied')
@section('code', '403')
@section('eyebrow', 'Access denied')
@section('heading', 'এই পেজে আপনার অনুমতি নেই')
@section('message', 'আপনার account-এ এই feature access করার permission নেই। প্রয়োজন হলে super admin-এর সাথে যোগাযোগ করুন।')
