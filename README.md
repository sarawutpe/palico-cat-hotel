# LARAVEL 8 | PALICO CAT HOTEL

## PHP DB

PHP Version: 7.4
Laravel: 8
MariaDB

## Web

-   CSS: Bootstrap 4
-   Theme: CoreUI https://coreui.io
-   Fonts: DB Heavent
-   sweetalert2 https://sweetalert2.github.io/
-   toastr https://codeseven.github.io/toastr/demo.html
-   dayjs https://day.js.org/en/

-   composer require mailjet/mailjet-apiv3-php

## Laravel Commands

$ run
php artisan serve

# palico-cat-hotel

# model

php artisan make:model Member -m

## Database

-   dbname palico_cat_hotel_db

php artisan make:controller MemberController

# create table
php artisan make:migration [CREATE_TABLE_NAME]

# migrate
php artisan migrate

R all cache
php artisan cache:clear


# Route cache cleared!

php artisan route:clear

# make middleware

$ php artisan make:middleware [NAME_HERE]

# Knowledge
Rent Status:
PENDING
RESERVED
CHECKED_IN
CHECKED_OUT
COMPLETED
CANCELED

Pay Status:
PENDING
PAYING
COMPLETED
CANCELED


[
    {
        "rent_id": 25,
        "rent_datetime": "2023-08-29 00:23:37",
        "rent_status": "PENDING",
        "rent_price": "44",
        "in_datetime": "2023-08-29 00:00:00",
        "out_datetime": "2023-08-31 00:00:00",
        "member_id": 1,
        "employee_in": null,
        "employee_pay": null,
        "room_id": 4,
        "pay_status": "PENDING",
        "created_at": "2023-08-28T17:23:37.000000Z",
        "updated_at": "2023-08-28T17:23:37.000000Z",
        "member": {
            "member_id": 1,
            "member_name": "นายเอ",
            "member_user": "m",
            "member_pass": "edd61e3056505610047ac6d583b8960d",
            "member_email": "sd1@gmail.com",
            "member_address": "123 456",
            "member_phone": "1111111111",
            "member_facebook": "fb 2",
            "member_lineid": "line 2",
            "member_img": "Ng0KX76KX2.jpg",
            "created_at": "2023-07-31T08:31:10.000000Z",
            "updated_at": "2023-08-15T15:31:41.000000Z"
        },
        "room": {
            "room_id": 4,
            "room_name": "room aa",
            "room_type": "M",
            "room_price": "22.00",
            "room_detail": "detail",
            "room_img": "6nMMyHJFyy.jpg",
            "room_limit": 4,
            "created_at": "2023-08-09T15:08:18.000000Z",
            "updated_at": "2023-08-12T10:22:34.000000Z"
        },
        "service": null,
        "checkin": {
            "checkin_id": 21,
            "rent_id": 25,
            "checkin_detail": null,
            "checkin_status": 0,
            "created_at": "2023-08-28T17:23:37.000000Z",
            "updated_at": "2023-08-28T17:23:37.000000Z",
            "checkin_cats": [
                {
                    "checkin_cat_id": 11,
                    "checkin_id": 21,
                    "cat_id": 19,
                    "created_at": "2023-08-28T17:23:37.000000Z",
                    "updated_at": "2023-08-28T17:23:37.000000Z",
                    "cat": {
                        "cat_id": 19,
                        "cat_name": "แมวจา",
                        "cat_age": "11",
                        "cat_sex": "MALE",
                        "cat_color": "",
                        "cat_gen": "undefined",
                        "cat_ref": "undefined",
                        "cat_img": "L5f90AoYEw.jpg",
                        "member_id": 1,
                        "cat_accessory": "",
                        "is_active": 1,
                        "created_at": "2023-08-20T08:20:45.000000Z",
                        "updated_at": "2023-08-27T12:40:59.000000Z"
                    }
                },
                {
                    "checkin_cat_id": 12,
                    "checkin_id": 21,
                    "cat_id": 26,
                    "created_at": "2023-08-28T17:23:37.000000Z",
                    "updated_at": "2023-08-28T17:23:37.000000Z",
                    "cat": {
                        "cat_id": 26,
                        "cat_name": "cat 1",
                        "cat_age": "11",
                        "cat_sex": "MALE",
                        "cat_color": "ดำ",
                        "cat_gen": "ทั่วไป",
                        "cat_ref": "undefined",
                        "cat_img": "L5f90AoYEw.jpg",
                        "member_id": 1,
                        "cat_accessory": "test 1",
                        "is_active": 1,
                        "created_at": "2023-08-20T08:25:30.000000Z",
                        "updated_at": "2023-08-25T17:13:35.000000Z"
                    }
                },
                {
                    "checkin_cat_id": 13,
                    "checkin_id": 21,
                    "cat_id": 24,
                    "created_at": "2023-08-28T17:23:37.000000Z",
                    "updated_at": "2023-08-28T17:23:37.000000Z",
                    "cat": {
                        "cat_id": 24,
                        "cat_name": "ไฮ",
                        "cat_age": "11",
                        "cat_sex": "MALE",
                        "cat_color": "ดำ",
                        "cat_gen": "undefined",
                        "cat_ref": "undefined",
                        "cat_img": null,
                        "member_id": 1,
                        "cat_accessory": "",
                        "is_active": 1,
                        "created_at": "2023-08-20T08:22:19.000000Z",
                        "updated_at": "2023-08-20T08:22:19.000000Z"
                    }
                },
                {
                    "checkin_cat_id": 14,
                    "checkin_id": 21,
                    "cat_id": 25,
                    "created_at": "2023-08-28T17:23:37.000000Z",
                    "updated_at": "2023-08-28T17:23:37.000000Z",
                    "cat": {
                        "cat_id": 25,
                        "cat_name": "cat 2",
                        "cat_age": "11",
                        "cat_sex": "MALE",
                        "cat_color": "ดำ",
                        "cat_gen": "thai",
                        "cat_ref": "undefined",
                        "cat_img": null,
                        "member_id": 1,
                        "cat_accessory": "test 2",
                        "is_active": 1,
                        "created_at": "2023-08-20T08:23:36.000000Z",
                        "updated_at": "2023-08-25T17:13:40.000000Z"
                    }
                }
            ]
        }
    },
    {
        "rent_id": 24,
        "rent_datetime": "2023-08-28 23:28:44",
        "rent_status": "PENDING",
        "rent_price": "66",
        "in_datetime": "2023-08-28 00:00:00",
        "out_datetime": "2023-08-31 00:00:00",
        "member_id": 1,
        "employee_in": null,
        "employee_pay": null,
        "room_id": 4,
        "pay_status": "PENDING",
        "created_at": "2023-08-28T16:28:44.000000Z",
        "updated_at": "2023-08-28T16:28:44.000000Z",
        "member": {
            "member_id": 1,
            "member_name": "นายเอ",
            "member_user": "m",
            "member_pass": "edd61e3056505610047ac6d583b8960d",
            "member_email": "sd1@gmail.com",
            "member_address": "123 456",
            "member_phone": "1111111111",
            "member_facebook": "fb 2",
            "member_lineid": "line 2",
            "member_img": "Ng0KX76KX2.jpg",
            "created_at": "2023-07-31T08:31:10.000000Z",
            "updated_at": "2023-08-15T15:31:41.000000Z"
        },
        "room": {
            "room_id": 4,
            "room_name": "room aa",
            "room_type": "M",
            "room_price": "22.00",
            "room_detail": "detail",
            "room_img": "6nMMyHJFyy.jpg",
            "room_limit": 4,
            "created_at": "2023-08-09T15:08:18.000000Z",
            "updated_at": "2023-08-12T10:22:34.000000Z"
        },
        "service": null,
        "checkin": {
            "checkin_id": 20,
            "rent_id": 24,
            "checkin_detail": null,
            "checkin_status": 0,
            "created_at": "2023-08-28T16:28:45.000000Z",
            "updated_at": "2023-08-28T16:28:45.000000Z",
            "checkin_cats": [
                {
                    "checkin_cat_id": 9,
                    "checkin_id": 20,
                    "cat_id": 26,
                    "created_at": "2023-08-28T16:28:45.000000Z",
                    "updated_at": "2023-08-28T16:28:45.000000Z",
                    "cat": {
                        "cat_id": 26,
                        "cat_name": "cat 1",
                        "cat_age": "11",
                        "cat_sex": "MALE",
                        "cat_color": "ดำ",
                        "cat_gen": "ทั่วไป",
                        "cat_ref": "undefined",
                        "cat_img": "L5f90AoYEw.jpg",
                        "member_id": 1,
                        "cat_accessory": "test 1",
                        "is_active": 1,
                        "created_at": "2023-08-20T08:25:30.000000Z",
                        "updated_at": "2023-08-25T17:13:35.000000Z"
                    }
                },
                {
                    "checkin_cat_id": 10,
                    "checkin_id": 20,
                    "cat_id": 24,
                    "created_at": "2023-08-28T16:28:45.000000Z",
                    "updated_at": "2023-08-28T16:28:45.000000Z",
                    "cat": {
                        "cat_id": 24,
                        "cat_name": "ไฮ",
                        "cat_age": "11",
                        "cat_sex": "MALE",
                        "cat_color": "ดำ",
                        "cat_gen": "undefined",
                        "cat_ref": "undefined",
                        "cat_img": null,
                        "member_id": 1,
                        "cat_accessory": "",
                        "is_active": 1,
                        "created_at": "2023-08-20T08:22:19.000000Z",
                        "updated_at": "2023-08-20T08:22:19.000000Z"
                    }
                }
            ]
        }
    },
    {
        "rent_id": 22,
        "rent_datetime": "2023-08-27 19:21:52",
        "rent_status": "PENDING",
        "rent_price": "22",
        "in_datetime": "2023-08-27 00:00:00",
        "out_datetime": "2023-08-28 00:00:00",
        "member_id": 1,
        "employee_in": null,
        "employee_pay": null,
        "room_id": 4,
        "pay_status": "PENDING",
        "created_at": "2023-08-27T12:21:52.000000Z",
        "updated_at": "2023-08-27T12:21:52.000000Z",
        "member": {
            "member_id": 1,
            "member_name": "นายเอ",
            "member_user": "m",
            "member_pass": "edd61e3056505610047ac6d583b8960d",
            "member_email": "sd1@gmail.com",
            "member_address": "123 456",
            "member_phone": "1111111111",
            "member_facebook": "fb 2",
            "member_lineid": "line 2",
            "member_img": "Ng0KX76KX2.jpg",
            "created_at": "2023-07-31T08:31:10.000000Z",
            "updated_at": "2023-08-15T15:31:41.000000Z"
        },
        "room": {
            "room_id": 4,
            "room_name": "room aa",
            "room_type": "M",
            "room_price": "22.00",
            "room_detail": "detail",
            "room_img": "6nMMyHJFyy.jpg",
            "room_limit": 4,
            "created_at": "2023-08-09T15:08:18.000000Z",
            "updated_at": "2023-08-12T10:22:34.000000Z"
        },
        "service": {
            "service_id": 8,
            "service_detail": "test",
            "rent_id": 22,
            "created_at": "2023-08-27T12:24:15.000000Z",
            "updated_at": "2023-08-27T12:24:15.000000Z",
            "service_lists": []
        },
        "checkin": {
            "checkin_id": 18,
            "rent_id": 22,
            "checkin_detail": null,
            "checkin_status": 0,
            "created_at": "2023-08-27T12:21:53.000000Z",
            "updated_at": "2023-08-27T12:21:53.000000Z",
            "checkin_cats": []
        }
    },
    {
        "rent_id": 19,
        "rent_datetime": "2023-08-27 18:55:18",
        "rent_status": "PENDING",
        "rent_price": "22",
        "in_datetime": "2023-08-27 00:00:00",
        "out_datetime": "2023-08-28 00:00:00",
        "member_id": 1,
        "employee_in": null,
        "employee_pay": null,
        "room_id": 4,
        "pay_status": "PENDING",
        "created_at": "2023-08-27T11:55:18.000000Z",
        "updated_at": "2023-08-27T11:55:18.000000Z",
        "member": {
            "member_id": 1,
            "member_name": "นายเอ",
            "member_user": "m",
            "member_pass": "edd61e3056505610047ac6d583b8960d",
            "member_email": "sd1@gmail.com",
            "member_address": "123 456",
            "member_phone": "1111111111",
            "member_facebook": "fb 2",
            "member_lineid": "line 2",
            "member_img": "Ng0KX76KX2.jpg",
            "created_at": "2023-07-31T08:31:10.000000Z",
            "updated_at": "2023-08-15T15:31:41.000000Z"
        },
        "room": {
            "room_id": 4,
            "room_name": "room aa",
            "room_type": "M",
            "room_price": "22.00",
            "room_detail": "detail",
            "room_img": "6nMMyHJFyy.jpg",
            "room_limit": 4,
            "created_at": "2023-08-09T15:08:18.000000Z",
            "updated_at": "2023-08-12T10:22:34.000000Z"
        },
        "service": {
            "service_id": 9,
            "service_detail": "test",
            "rent_id": 19,
            "created_at": "2023-08-27T12:24:29.000000Z",
            "updated_at": "2023-08-27T12:24:29.000000Z",
            "service_lists": []
        },
        "checkin": {
            "checkin_id": 15,
            "rent_id": 19,
            "checkin_detail": null,
            "checkin_status": 0,
            "created_at": "2023-08-27T11:55:18.000000Z",
            "updated_at": "2023-08-27T11:55:18.000000Z",
            "checkin_cats": []
        }
    },
    {
        "rent_id": 15,
        "rent_datetime": "2023-08-14 21:18:20",
        "rent_status": "RESERVED",
        "rent_price": "22",
        "in_datetime": "2023-09-01 06:01:00",
        "out_datetime": "2023-09-02 19:01:00",
        "member_id": 1,
        "employee_in": 1,
        "employee_pay": 1,
        "room_id": 2,
        "pay_status": "PAYING",
        "created_at": "2023-08-14T14:18:20.000000Z",
        "updated_at": "2023-08-20T13:04:41.000000Z",
        "member": {
            "member_id": 1,
            "member_name": "นายเอ",
            "member_user": "m",
            "member_pass": "edd61e3056505610047ac6d583b8960d",
            "member_email": "sd1@gmail.com",
            "member_address": "123 456",
            "member_phone": "1111111111",
            "member_facebook": "fb 2",
            "member_lineid": "line 2",
            "member_img": "Ng0KX76KX2.jpg",
            "created_at": "2023-07-31T08:31:10.000000Z",
            "updated_at": "2023-08-15T15:31:41.000000Z"
        },
        "room": {
            "room_id": 2,
            "room_name": "room3",
            "room_type": "S",
            "room_price": "22.00",
            "room_detail": "detail",
            "room_img": "voj0Dpvcra.jpg",
            "room_limit": 2,
            "created_at": "2023-08-09T15:02:35.000000Z",
            "updated_at": "2023-08-27T10:31:53.000000Z"
        },
        "service": {
            "service_id": 6,
            "service_detail": "test",
            "rent_id": 15,
            "created_at": "2023-08-19T18:15:37.000000Z",
            "updated_at": "2023-08-19T18:15:37.000000Z",
            "service_lists": [
                {
                    "service_list_id": 34,
                    "service_id": 6,
                    "service_list_name": null,
                    "service_list_datetime": null,
                    "service_list_checked": 0,
                    "created_at": "2023-08-20T07:16:12.000000Z",
                    "updated_at": "2023-08-20T07:16:12.000000Z"
                },
                {
                    "service_list_id": 35,
                    "service_id": 6,
                    "service_list_name": null,
                    "service_list_datetime": null,
                    "service_list_checked": 0,
                    "created_at": "2023-08-20T07:16:12.000000Z",
                    "updated_at": "2023-08-20T07:16:12.000000Z"
                }
            ]
        },
        "checkin": {
            "checkin_id": 12,
            "rent_id": 15,
            "checkin_detail": "test",
            "checkin_status": 1,
            "created_at": "2023-08-20T12:50:52.000000Z",
            "updated_at": "2023-08-20T12:50:52.000000Z",
            "checkin_cats": []
        }
    },
    {
        "rent_id": 13,
        "rent_datetime": "2023-08-13 14:44:40",
        "rent_status": "PENDING",
        "rent_price": "22.00",
        "in_datetime": "2023-08-13 14:44:00",
        "out_datetime": "2023-08-13 14:44:00",
        "member_id": 1,
        "employee_in": 1,
        "employee_pay": 1,
        "room_id": 5,
        "pay_status": "PENDING",
        "created_at": "2023-08-13T07:44:41.000000Z",
        "updated_at": "2023-08-20T12:43:55.000000Z",
        "member": {
            "member_id": 1,
            "member_name": "นายเอ",
            "member_user": "m",
            "member_pass": "edd61e3056505610047ac6d583b8960d",
            "member_email": "sd1@gmail.com",
            "member_address": "123 456",
            "member_phone": "1111111111",
            "member_facebook": "fb 2",
            "member_lineid": "line 2",
            "member_img": "Ng0KX76KX2.jpg",
            "created_at": "2023-07-31T08:31:10.000000Z",
            "updated_at": "2023-08-15T15:31:41.000000Z"
        },
        "room": {
            "room_id": 5,
            "room_name": "room bb",
            "room_type": "M",
            "room_price": "22.00",
            "room_detail": "detail",
            "room_img": "6nMMyHJFyy.jpg",
            "room_limit": 5,
            "created_at": "2023-08-09T15:08:18.000000Z",
            "updated_at": "2023-08-12T10:22:34.000000Z"
        },
        "service": {
            "service_id": 7,
            "service_detail": "test",
            "rent_id": 13,
            "created_at": "2023-08-19T19:31:02.000000Z",
            "updated_at": "2023-08-19T19:31:02.000000Z",
            "service_lists": []
        },
        "checkin": null
    },
    {
        "rent_id": 14,
        "rent_datetime": "2023-08-13 14:45:40",
        "rent_status": "PENDING",
        "rent_price": "22.00",
        "in_datetime": "2023-08-13 14:45:00",
        "out_datetime": "2023-08-13 14:45:00",
        "member_id": 1,
        "employee_in": 1,
        "employee_pay": 1,
        "room_id": 5,
        "pay_status": "PENDING",
        "created_at": "2023-08-13T07:45:40.000000Z",
        "updated_at": "2023-08-20T12:39:49.000000Z",
        "member": {
            "member_id": 1,
            "member_name": "นายเอ",
            "member_user": "m",
            "member_pass": "edd61e3056505610047ac6d583b8960d",
            "member_email": "sd1@gmail.com",
            "member_address": "123 456",
            "member_phone": "1111111111",
            "member_facebook": "fb 2",
            "member_lineid": "line 2",
            "member_img": "Ng0KX76KX2.jpg",
            "created_at": "2023-07-31T08:31:10.000000Z",
            "updated_at": "2023-08-15T15:31:41.000000Z"
        },
        "room": {
            "room_id": 5,
            "room_name": "room bb",
            "room_type": "M",
            "room_price": "22.00",
            "room_detail": "detail",
            "room_img": "6nMMyHJFyy.jpg",
            "room_limit": 5,
            "created_at": "2023-08-09T15:08:18.000000Z",
            "updated_at": "2023-08-12T10:22:34.000000Z"
        },
        "service": {
            "service_id": 5,
            "service_detail": "test",
            "rent_id": 14,
            "created_at": "2023-08-19T18:15:13.000000Z",
            "updated_at": "2023-08-19T18:15:13.000000Z",
            "service_lists": [
                {
                    "service_list_id": 33,
                    "service_id": 5,
                    "service_list_name": null,
                    "service_list_datetime": null,
                    "service_list_checked": 1,
                    "created_at": "2023-08-20T06:40:37.000000Z",
                    "updated_at": "2023-08-20T07:14:08.000000Z"
                }
            ]
        },
        "checkin": {
            "checkin_id": 2,
            "rent_id": 14,
            "checkin_detail": "test 2",
            "checkin_status": 1,
            "created_at": "2023-08-20T12:23:55.000000Z",
            "updated_at": "2023-08-20T12:39:49.000000Z",
            "checkin_cats": []
        }
    },
    {
        "rent_id": 12,
        "rent_datetime": "2023-08-13 14:30:42",
        "rent_status": "RESERVED",
        "rent_price": "22.00",
        "in_datetime": "2023-08-13 20:30:00",
        "out_datetime": "2023-08-13 14:30:00",
        "member_id": 1,
        "employee_in": 1,
        "employee_pay": 1,
        "room_id": 5,
        "pay_status": "PENDING",
        "created_at": "2023-08-13T07:30:43.000000Z",
        "updated_at": "2023-08-20T12:39:45.000000Z",
        "member": {
            "member_id": 1,
            "member_name": "นายเอ",
            "member_user": "m",
            "member_pass": "edd61e3056505610047ac6d583b8960d",
            "member_email": "sd1@gmail.com",
            "member_address": "123 456",
            "member_phone": "1111111111",
            "member_facebook": "fb 2",
            "member_lineid": "line 2",
            "member_img": "Ng0KX76KX2.jpg",
            "created_at": "2023-07-31T08:31:10.000000Z",
            "updated_at": "2023-08-15T15:31:41.000000Z"
        },
        "room": {
            "room_id": 5,
            "room_name": "room bb",
            "room_type": "M",
            "room_price": "22.00",
            "room_detail": "detail",
            "room_img": "6nMMyHJFyy.jpg",
            "room_limit": 5,
            "created_at": "2023-08-09T15:08:18.000000Z",
            "updated_at": "2023-08-12T10:22:34.000000Z"
        },
        "service": {
            "service_id": 1,
            "service_detail": "test",
            "rent_id": 12,
            "created_at": "2023-08-19T17:28:42.000000Z",
            "updated_at": "2023-08-19T17:28:42.000000Z",
            "service_lists": [
                {
                    "service_list_id": 2,
                    "service_id": 1,
                    "service_list_name": "this is name 2",
                    "service_list_datetime": "2023-08-19 20:37:20",
                    "service_list_checked": 1,
                    "created_at": "2023-08-20T06:35:18.000000Z",
                    "updated_at": "2023-08-20T06:35:18.000000Z"
                },
                {
                    "service_list_id": 3,
                    "service_id": 1,
                    "service_list_name": "this is name 3",
                    "service_list_datetime": "2023-08-19 20:37:20",
                    "service_list_checked": 1,
                    "created_at": "2023-08-20T06:35:18.000000Z",
                    "updated_at": "2023-08-20T07:13:53.000000Z"
                },
                {
                    "service_list_id": 15,
                    "service_id": 1,
                    "service_list_name": "this is name 4",
                    "service_list_datetime": "2023-08-20 14:26:05",
                    "service_list_checked": 0,
                    "created_at": "2023-08-20T06:23:29.000000Z",
                    "updated_at": "2023-08-20T07:14:05.000000Z"
                },
                {
                    "service_list_id": 16,
                    "service_id": 1,
                    "service_list_name": "asdasd3",
                    "service_list_datetime": "2023-08-20 13:35:41",
                    "service_list_checked": 0,
                    "created_at": "2023-08-20T06:24:13.000000Z",
                    "updated_at": "2023-08-20T07:14:04.000000Z"
                },
                {
                    "service_list_id": 18,
                    "service_id": 1,
                    "service_list_name": "asdasdasd3",
                    "service_list_datetime": "2023-08-20 13:35:44",
                    "service_list_checked": 1,
                    "created_at": "2023-08-20T06:24:15.000000Z",
                    "updated_at": "2023-08-20T07:13:54.000000Z"
                },
                {
                    "service_list_id": 26,
                    "service_id": 1,
                    "service_list_name": "18+++ 18++",
                    "service_list_datetime": "2023-08-20 13:35:45",
                    "service_list_checked": 1,
                    "created_at": "2023-08-20T06:34:27.000000Z",
                    "updated_at": "2023-08-20T07:13:55.000000Z"
                },
                {
                    "service_list_id": 27,
                    "service_id": 1,
                    "service_list_name": "19++",
                    "service_list_datetime": "2023-08-20 13:35:46",
                    "service_list_checked": 1,
                    "created_at": "2023-08-20T06:34:56.000000Z",
                    "updated_at": "2023-08-20T07:14:04.000000Z"
                },
                {
                    "service_list_id": 29,
                    "service_id": 1,
                    "service_list_name": "x2",
                    "service_list_datetime": "2023-08-20 19:46:46",
                    "service_list_checked": 1,
                    "created_at": "2023-08-20T06:35:52.000000Z",
                    "updated_at": "2023-08-20T07:14:03.000000Z"
                }
            ]
        },
        "checkin": {
            "checkin_id": 1,
            "rent_id": 12,
            "checkin_detail": "test 123",
            "checkin_status": 1,
            "created_at": "2023-08-20T12:05:43.000000Z",
            "updated_at": "2023-08-20T12:39:00.000000Z",
            "checkin_cats": []
        }
    }
]

