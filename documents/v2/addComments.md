# 添加评论

[添加音乐评论](#添加音乐评论)
[添加专辑评论](#添加专辑评论)


### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| body | string | 评论内容 |
| reply_user | Integer | 被回复者 默认为0 |


## 添加音乐评论

```
POST /music/{music}/comments
```

#### Response

```
Status: 201 Created
```

```json5
{
    "message": [
        "操作成功"
    ],
    "comment": { // 评论信息
        "user_id": 1,
        "reply_user": 0,
        "target_user": 0,
        "body": "辣鸡啊啊啊啊",
        "commentable_type": "musics",
        "commentable_id": 1,
        "updated_at": "2017-07-24 09:12:03",
        "created_at": "2017-07-24 09:12:03",
        "id": 13
    }
}
```

## 添加专辑评论

```
POST /music/specials/{special}/comments
```

#### Response

```
Status: 200 OK
```

```json5
{
    "message": [
        "操作成功"
    ],
    "comment": { // 评论信息
        "user_id": 1,
        "reply_user": 0,
        "target_user": 0,
        "body": "因吹斯听",
        "commentable_type": "music_specials",
        "commentable_id": 1,
        "updated_at": "2017-07-24 09:12:03",
        "created_at": "2017-07-24 09:12:03",
        "id": 13
    }
}
```