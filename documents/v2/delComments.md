# 删除评论

[删除音乐评论](#删除音乐评论)
[删除专辑评论](#删除专辑评论)


### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| body | string | 评论内容 |
| reply_user | Integer | 被回复者 默认为0 |


## 删除音乐评论

```
DELETE /music/{music}/comments/{comment}
```

#### Response

```
Status: 204 No Content
```

## 删除专辑评论

```
DELETE /music/specials/{special}/comments/{comment}
```

#### Response

```
Status: 204 No Conetent
```