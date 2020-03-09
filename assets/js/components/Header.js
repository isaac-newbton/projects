import React from 'react';
import { Navbar, NavDropdown, Nav } from 'react-bootstrap';

const Header = props => {
	const AccountLink = () => {
		return props.user ? (
			<Nav.Link href='/logout'>Logout</Nav.Link>
		) : (
			<>
				<Nav.Link href='/login'>Login</Nav.Link>
				<Nav.Link href='/signup'>Signup</Nav.Link>
			</>
		);
	};
	return (
		<Navbar
			collapseOnSelect
			expand='lg'
			bg='light'
			variant='light'
			className='mb-5'
		>
			<Navbar.Brand href='#home'>Projects App</Navbar.Brand>
			<Navbar.Toggle aria-controls='responsive-navbar-nav' />
			<Navbar.Collapse id='responsive-navbar-nav'>
				<Nav className='mr-auto'>
					<NavDropdown title='Projects' id='collasible-nav-dropdown'>
						<NavDropdown.Item href='/'>Create New</NavDropdown.Item>
						<NavDropdown.Item href='/projects'>
							View List
						</NavDropdown.Item>
					</NavDropdown>
				</Nav>
				<Nav>
					<AccountLink />
				</Nav>
			</Navbar.Collapse>
		</Navbar>
	);
};

export default Header;
