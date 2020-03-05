import React, { useState, useEffect } from 'react';

const UserAuthenticatedComponent = props => {
	const [a, set] = useState(false)
	useEffect(() => {
		fetch("/api/v1/auth")
		.then(resp => resp.json())
		.then(resp => resp.error ?? set(resp))
	}, [])
	return a ? props.children : null
}

export default UserAuthenticatedComponent;