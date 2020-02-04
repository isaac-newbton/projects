import React, { useState, useEffect } from 'react';
import Container from 'react-bootstrap/Container';
import { Row, Col } from 'react-bootstrap';
import { useParams } from 'react-router-dom';
import EditProject from './projectView/EditProject';
import ViewProject from './projectView/ViewProject';

const ProjectScreen = props => {
	const { encodedUuid } = useParams();
	const [isLoading, setIsLoading] = useState(false) // FIXME: make true when backend api is complete

	// TODO: complete this fetch!
	const [project, setProject] = useState({ // FIXME: make null when backend api is complete
		name: "the project name",
		uuid: 'asdf-1234-qwer-0987-lkjh',
		dueDate: '2020-02-04',
		viewUuid: 'ASasahyiuq34ASDGasg',
		editUuid: 'ASasahyiuq34ASDGasg',
		edit: true
	})

	const fetchProject = () => {
		fetch("http://127.0.0.1", {
			method: "POST",
			body: {
				'encodedUuid' : encodedUuid
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
			setProject(project)
		})
		.catch(error => {
			setIsLoading(false)
			return Promise.reject()
		})
	}
	// useEffect(fetchProject, []) // TODO: complete this after the backend api is completed

	const updateProjectHandler = event => {
		if (project[event.target.name] !== event.target.value){
			project[event.target.name] = event.target.value;
			setProject(project);
		}
	}

	if (isLoading === true) return "loading..."
	if (project){
		if (project.edit){
			return <EditProject updateProject={updateProjectHandler} project={project}/>
		}
		return <ViewProject project={project}/>
	}
	return "TODO: Handle this with a 404 component or something similar"
}

export default ProjectScreen;