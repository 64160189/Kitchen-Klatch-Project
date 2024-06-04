<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class postcontroller extends Controller
{
    function showpost(){
        $post = [
            [
                'title' => "ส้มตำปูปลาร้าสุดแซ่บ",
                'description' => "อธิบายส้มตำ",
                'image' => "images/64160189-Shinchan.png", // Path to the image file
                'ingredient' => ["มะละกอ", "ปู", "น้ำปลาร้า", "พริก"],
                'htc' => ["1.โขลกกระเทียม พริกสด และพริกแห้งพอแหลก", "2.ใส่มะเขือเทศ มะนาว น้ำมะขามเปียก มะละกอ คลุกให้เข้ากัน", "3.ปรุงรสด้วยน้ำปลาร้ น้ำตาลปี๊บ คลุกให้เข้ากัน"]
            ],
            [
                'title' => "ข้าวผัด",
                'description' => "อธิบายข้าวผัด",
                'image' => "images/64160189-Shinchan.png", // Path to the image file
                'ingredient' => ["ข้าวสวย", "กระเทียม", "ต้นหอม"],
                'htc' => ["1.ผัดกระเทียม", "2.ผัดข้าว", "3.ปรุงรส"]

            ],
            [
                'title' => "ไก่ทอดกรอบกรุ๊บกรั๊บ",
                'description' => "อธิบายไก่ทอด",
                'image' => "images/64160189-Shinchan.png", // Path to the image file
                'ingredient' => ["เนื้อไก่", "แป้ง"],
                'htc' => ["1.นำไก่ไปคลุกแป้ง", "2.ทอดในน้ำมันร้อนๆ"]
            ]
        ];

        return view('home', compact('post'));   
    }

    function insert(Request $request){
        $request->validate([
            'title'=>'required|max:75',
            'description'=>'required',
            'image'=>'required',
            'ingrediant'=>'required',
            'htc'=>'required'
        ]);
    }
}
