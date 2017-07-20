# 专辑列表

```
GET /music/specials
```

#### Response

```
Status: 201 OK
```
```json5
[
    {
        "id": 2, // 专辑id
        "created_at": "2017-03-15 17:04:31",
        "updated_at": "2017-06-27 18:40:56",
        "title": "少女情怀总是诗", // 专辑标题
        "intro": "耶嘿 杀乌鸡", // 专辑简介
        "storage": { // 专辑封面图
            "id": 108, // 图片id
            "size": "3024x3024" // 图片尺寸
        },
        "taste_count": 845, // 播放数
        "share_count": 21, // 分享数
        "comment_count": 97, // 评论数
        "collect_count": 9, // 收藏数
        "has_collect": true, // 是否已收藏
        "paid_node": { // 付费节点  为null则是免费
            "paid": true, // 是否已付费
            "node": 1, // 付费节点
            "amount": 200 // 付费金额
        }
    },
    {
        "id": 1,
        "created_at": "2017-03-15 17:01:17",
        "updated_at": "2017-07-20 03:39:00",
        "title": "干了这碗鸡血时速八千",
        "intro": "听听歌 抖抖腿",
        "storage": {
            "id": 108,
            "size": "3024x3024"
        },
        "taste_count": 1556,
        "share_count": 23,
        "comment_count": 137,
        "collect_count": 12,
        "has_collect": false,
        "paid_node": null
    }
]
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 20 ，获取条数 |
| max_id | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |

> 列表为倒序