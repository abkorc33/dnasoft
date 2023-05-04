package fastcampus.projectboard.domain;

import lombok.Getter;
import lombok.Setter;
import lombok.ToString;
import org.springframework.data.annotation.CreatedBy;
import org.springframework.data.annotation.CreatedDate;
import org.springframework.data.annotation.LastModifiedBy;
import org.springframework.data.annotation.LastModifiedDate;
import org.springframework.data.jpa.domain.support.AuditingEntityListener;

import javax.persistence.*;
import java.time.LocalDateTime;
import java.util.LinkedHashSet;
import java.util.Objects;
import java.util.Set;

@Getter
@ToString
//빠른 검색 가능하게끔 인덱싱. 너무 긴 내용은 인덱싱 최대범위에 못도달.
@Table(indexes = {
        @Index(columnList = "title"),
        @Index(columnList = "hashtag"),
        @Index(columnList = "createdAt"),
        @Index(columnList = "createdBy")
})
@EntityListeners(AuditingEntityListener.class)
@Entity
public class Article {

    @Id                     //PK
    @GeneratedValue(strategy = GenerationType.IDENTITY)         //autoincrement 위해
    private Long id;
    @Setter @Column(nullable = false) private String title;   //제목. length 디폴트가 255라서 생략
    @Setter @Column(nullable = false, length = 10000) private String content; //본문
    @Setter private String hashtag; //해시태그. null 가능

    //    한번만 쓸거라서 final.
    @ToString.Exclude
    @OrderBy("id")
    @OneToMany(mappedBy = "article", cascade = CascadeType.ALL)
    private final Set<ArticleComment> articleComments = new LinkedHashSet<>();

    // 메타데이터
    @CreatedDate @Column(nullable = false) private LocalDateTime createdAt;    //생성일시
    @CreatedBy @Column(nullable = false,length =100) private String createdBy;            //생성자
    @LastModifiedDate @Column(nullable = false) private LocalDateTime modifiedAt;   //수정일시
    @LastModifiedBy @Column(nullable = false,length =100) private String modifiedBy;           //수정자

    protected Article() {}
    private Article(String title, String content, String hashtag) {
        this.title = title;
        this.content = content;
        this.hashtag = hashtag;
    }
    public static Article of(String title, String content, String hashtag) {
        return new Article(title, content, hashtag);
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        Article article = (Article) o;
        return id != null && id.equals(article.id);
    }

    @Override
    public int hashCode() {
        return Objects.hash(id);
    }
}
