# 获取专辑详情

```
GET /music/specials/{special}
```

#### Response

```
Status: 201 OK
```
```json5
{
    "id": 2,
    "created_at": "2017-03-15 17:04:31",
    "updated_at": "2017-06-27 18:40:56",
    "title": "少女情怀总是诗",
    "intro": "耶嘿 杀乌鸡",
    "storage": {
        "id": 108,
        "size": "3024x3024"
    },
    "taste_count": 845,
    "share_count": 21,
    "comment_count": 97,
    "collect_count": 9,
    "paid_node": {
        "paid": true,
        "node": 1,
        "amount": 200
    },
    "has_collect": true, // 以上数据参见专辑列表
    "musics": [ // 音乐数据参见音乐详情
        {
            "id": 7,
            "created_at": "2017-04-17 15:27:59",
            "updated_at": "2017-07-06 03:53:04",
            "deleted_at": null,
            "title": "umbrella",
            "singer": {
                "id": 2,
                "created_at": "2017-03-16 17:22:18",
                "updated_at": "2017-03-16 17:22:20",
                "name": "佚名",
                "cover": {
                    "id": 1,
                    "size": "370x370"
                }
            },
            "storage": {
                "id": 112
            },
            "last_time": 300,
            "lyric": null,
            "taste_count": 0,
            "share_count": 0,
            "comment_count": 0,
            "has_like": true
        },
        {
            "id": 3,
            "created_at": "2017-03-16 17:21:09",
            "updated_at": "2017-07-06 08:01:18",
            "deleted_at": null,
            "title": "别碰我的人",
            "singer": {
                "id": 1,
                "created_at": "2017-03-16 17:22:04",
                "updated_at": "2017-03-16 17:22:08",
                "name": "群星",
                "cover": {
                    "id": 1,
                    "size": "370x370"
                }
            },
            "storage": { // 音乐付费时
                "id": 133,
                "amount": "200",
                "type": "download",
                "paid": "false",
                "paid_node": "12" 
            },
            "last_time": 200,
            "lyric": null,
            "taste_count": 297,
            "share_count": 0,
            "comment_count": 23,
            "has_like": true
        }
    ]
}
```

##### Not paid

```json5
{
    "message": [
        "请购买专辑"
    ],
    "paid_node": 9, // 付费节点
    "amount": 20 // 动态价格
}
```