package com.cos.blog.model;

import java.sql.Timestamp;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;

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
@Entity // User클래스가 MySQL에 테이블 생성 된다.
public class User {
	
	@Id // Primary key
	@GeneratedValue(strategy = GenerationType.IDENTITY) // 프로젝트에서 연결된 DB의 넘버링 전략을 따라간다. // auto_increment
	private int id; // 시퀀스, auto_increment, 비워놔도 자동으로 들어간다.
	
	@Column(nullable = false, length=30)
	private String username; // 아이디
	
	@Column(nullable = false, length=100) // 123456 => 해쉬(비밀번호 암호화)
	private String password;
	
	@Column(nullable = false, length=50)
	private String email;
	
	@ColumnDefault("'user'")
	private String role; // Enum을 쓰는게 좋다.(데이터의 도메인을 만들 수 있다.) // admin,user,manager
	
	@CreationTimestamp // 시간자동입력, 비워놔도 자동으로 들어간다.
	private Timestamp createDate;
}
