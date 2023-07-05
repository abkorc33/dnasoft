package com.cos.blog.test;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;
import lombok.RequiredArgsConstructor;

// @AllArgsConstructor // 전체 생성자
@NoArgsConstructor // 빈 생성자
@Data // getter, setter
// @RequiredArgsConstructor // final 붙은 애들에 대한 생성자를 만들어준다.
public class Member {
	// private으로 변수 만드는 이유: 변수에 direct로 접근해 값을 바꾸는 것을 방지 객체지향과 맞지 않기 때문
	// public 메서드를 통해 변수의 상태를 바꾸는게 맞다.(direct로 값 변경하지 말고)
	// final => 불변성 유지
	private int id;
	private String username;
	private String password;
	private String email;
	
	@Builder // 데이터베이스에서 자동으로 증가시킨다 (autoIncreament같은 개념)
	public Member(int id, String username, String password, String email) {
		this.id = id;
		this.username = username;
		this.password = password;
		this.email = email;
	}
	
	
}
