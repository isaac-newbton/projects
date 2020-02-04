import React, { useState, useEffect } from 'react';
import Container from 'react-bootstrap/Container';
import { Row, Col } from 'react-bootstrap';
import { useParams } from 'react-router-dom';

const ProjectScreen = props => {
	const { decodedUuid } = useParams();
	const [isLoading, setIsLoading] = useState(false) // FIXME: make true when backend api is complete

	// TODO: complete this fetch!
	const [project, setProject] = useState({name: "project name"}) // FIXME: make null when backend api is complete

	const fetchProject = () => {
		fetch("http://127.0.0.1", {
			method: "POST",
			body: {
				'decodedUuid' : decodedUuid
			}
		})
		.then(resp => {
			if (!resp.ok){
				throw new Error("network response failure")
			} else {
				return resp.json()
			}
		})
		.then(project => {
			console.log(project)
			setProject(project)
		})
		.catch(error => {
			setIsLoading(false)
			return Promise.reject()
		})
	}
	// useEffect(fetchProject, []) // TODO: complete this after the backend api is completed

	if (isLoading === true) return "loading..."
	if (project){
		return (
			<Container>
				<Row>
					<Col>
						<h1>{project.name}</h1>
						<p>decoded uuid param: {decodedUuid}</p>
					</Col>
				</Row>
			</Container>
		)
	}
	return "TODO: Handle this with a 404 component or something similar"
}

export default ProjectScreen;