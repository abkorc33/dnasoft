package com.cos.blog.model;

import java.sql.Timestamp;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;

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
@Entity // User클래스가 MySQL에 테이블 생성 된다.
public class Reply {

	@Id // Primary key
	@GeneratedValue(strategy = GenerationType.IDENTITY) // 프로젝트에서 연결된 DB의 넘버링 전략을 따라간다. // auto_increment
	private int id; // 시퀀스, auto_increment, 비워놔도 자동으로 들어간다.
	
	@Column(nullable = false, length=200) 
	private String content;
	
	@ManyToOne // 여러개의 답글은 하나의 개시글에 존재한다.
	@JoinColumn(name="boardId")
	private Board board;
	
	@ManyToOne // 여러개의 답글을 하나의 유저가 쓸 수 있다.
	@JoinColumn(name="userId") // 외래키 지정, 필드명 userId로 생성됨
	private User user;
	
	@CreationTimestamp
	private Timestamp createDate;
}
