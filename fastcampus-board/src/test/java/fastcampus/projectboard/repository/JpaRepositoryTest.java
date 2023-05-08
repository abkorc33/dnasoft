package fastcampus.projectboard.repository;

import fastcampus.projectboard.config.JpaConfig;
import fastcampus.projectboard.domain.Article;
import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.orm.jpa.DataJpaTest;
import org.springframework.context.annotation.Import;

import static org.assertj.core.api.Assertions.*;

@DisplayName("JPA 연결 테스트")
@Import(JpaConfig.class)
@DataJpaTest
class JpaRepositoryTest {
    private final ArticleRepository articleRepository;
    private final ArticleCommentRepository articleCommentRepository;

    public JpaRepositoryTest(
            @Autowired ArticleRepository articleRepository,
            @Autowired ArticleCommentRepository articleCommentRepository
    ) {
        this.articleRepository = articleRepository;
        this.articleCommentRepository = articleCommentRepository;
    }
    //@DisplayName("select 테스트")
    //@DisplayName("insert 테스트")
    //@DisplayName("update 테스트")
    @DisplayName("delete 테스트")
    @Test
    //void givenTestData_whenSelecting_thenWorksFine(){ // select
    //void givenTestData_whenInserting_thenWorksFine() { // insert
    //void givenTestData_whenUpdating_thenWorksFine() { // update
    void givenTestData_whenDeleting_thenWorksFine() {
        //Given
        //long previousCount = articleRepository.count(); // insert
        /*Article article = articleRepository.findById(1L).orElseThrow(); // update
        String updatedHashtag = "#springboot";
        article.setHashtag(updatedHashtag);*/
        Article article = articleRepository.findById(1L).orElseThrow(); // update
        long previousArticleCount = articleRepository.count();
        long previousArticleCommentCount = articleCommentRepository.count();
        int deletedCommentsSize = article.getArticleComments().size();

        //When //select를 위해 article로 하고 findall해서 article list로 받아온다.
        //List<Article> articles = articleRepository.findAll(); //select
        //Article savedArticle = articleRepository.save(Article.of("new article", "new content", "#spring")); // insert
        //Article savedArticle = articleRepository.saveAndFlush(article); // update
        articleRepository.delete(article);

        //Then // articles가 notnull이면 좋겠고 size는 현재 2개(jpa기능을 잘 만들었다면 select가 잘 될것)
        /*assertThat(articles)
                .isNotNull()
                .hasSize( 1); // select */
        //assertThat(articleRepository.count()).isEqualTo(previousCount + 1); //insert
        //assertThat(savedArticle).hasFieldOrPropertyWithValue("hashtag", updatedHashtag); // update
        assertThat(articleRepository.count()).isEqualTo(previousArticleCount - 1);
        assertThat(articleCommentRepository.count()).isEqualTo(previousArticleCommentCount - 1);
    }
}