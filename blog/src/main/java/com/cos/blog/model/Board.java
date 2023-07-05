package com.cos.blog.model;

import java.sql.Timestamp;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.Lob;
import javax.persistence.ManyToOne;

import org.hibernate.annotations.ColumnDefault;
import org.hibernate.annotations.CreationTimestamp;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data // getter, setter
@NoArgsConstructor // 빈 생성자
@AllArgsConstructor // 전체생성자
@Builder // 빌더패턴!!
//ORM => Java(다른언어) Object => 테이블로 매핑해주는 기술.
@Entity // Board클래스가 MySQL에 테이블 생성 된다.
public class Board {
	
	@Id // Primary key
	@GeneratedValue(strategy = GenerationType.IDENTITY) // 프로젝트에서 연결된 DB의 넘버링 전략을 따라간다. // auto_increment
	private int id; // 시퀀스, auto_increment, 비워놔도 자동으로 들어간다.
	
	@Column(nullable = false, length=100)
	private String title;
	
	@Lob // 대용량 데이터
	private String content; // 섬머노트 라이브러리 <html>태그가 섞여서 디자인이 됨.
	
	@ColumnDefault("0")
	private int count; // 조회수
	
	// private int userId; ORM(jpa) 에서는 외래키(키값) 으로 찾지 않고 오브젝트로 바로 사용한다.
	@ManyToOne // board가 many, user가 one
	@JoinColumn(name="userId") // 외래키 지정, 필드명 userId로 생성됨
	private User user; // DB는 오브젝트를 저장할 수 없다. FK, 자바는 오브젝트를 저장할 수 있다. => 자바와 DB가 충돌 => 자바가 DB에 맞춰서 키값을 저장한다.
	
	@CreationTimestamp
	private Timestamp createDate;
}
