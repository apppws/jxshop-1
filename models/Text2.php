<?php
namespace models;

class Text2 extends Model
{
    // 设置这个模型对应的表
    protected $table = 'text2';
    // 设置允许接收的字段
    protected $fillable = ['title','conent'];
}