# 获取音乐详情

```
GET /music/{music}
```

#### Response

```
Status: 201 OK
```
```json5
{
    "id": 1, // 音乐id
    "created_at": "2017-03-16 17:11:26",
    "updated_at": "2017-07-20 03:39:00",
    "deleted_at": null,
    "title": "水手公园", // 音乐标题
    "singer": { // 歌手信息
        "id": 1, // 歌手id
        "created_at": "2017-03-16 17:22:04",
        "updated_at": "2017-03-16 17:22:08",
        "name": "群星", // 歌手名称
        "cover": { // 歌手图片
            "id": 108, // 图片id
            "size": "3024x3024" // 图片尺寸
        }
    },
    "storage": { // 音乐附件信息
        "id": 105, // 附件id
        "amount": 100, // 付费金额 音乐免费时该字段不存在
        "type": "download", // 付费类型  音乐免费时该字段不存在
        "paid": true, // 是否已付费 音乐免费时 该字段不存在
        "paid_node": 2 // 付费节点  音乐免费时 该字段不存在
    },
    "last_time": 180, // 歌曲时间(app暂时自行下载解析时间)
    "lyric": "lalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallalalaallal", // 歌词
    "taste_count": 314, // 播放数
    "share_count": 0, // 分享数
    "comment_count": 12, // 评论数
    "has_like": true // 是否已赞
}
```