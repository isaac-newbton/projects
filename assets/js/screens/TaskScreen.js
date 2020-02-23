import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import EditTask from './taskView/EditTask';

const TaskScreen = props => {
	const { encodedUuid } = useParams('encodedUuid')
	const [isLoading, setIsLoading] = useState(false)

	const [task, setTask] = useState(null)

	const fetchTask = () => {
		fetch('/api/v1/task/view', {
			method: 'POST',
			body: JSON.stringify({
				'encodedUuid': encodedUuid
			})
		}).then(r => r.json()).then(task => {
			setTask(task)
			setIsLoading(false)
		}).catch(e => {
			return Promise.reject()
		})
	}
	useEffect(fetchTask, [])

	const saveTask = () => {
		fetch('/api/v1/task/update', {
			method: 'POST',
			body: JSON.stringify({
				'task': task
			})
		}).then(r => r.json()).then(task => {
			setTask(task)
		}).catch(e => {
			return Promise.reject()
		})
	}

	const updateTaskHandler = event => {
		if (task[event.target.name] !== event.target.value) {
			task[event.target.name] = event.target.value
			setTask(task)
			saveTask()
		}
	}

	const HandleCommentFormSubmit = props => {
		fetch("/api/v1/comment/create", {
			method: 'POST',
			body: JSON.stringify({
				taskUuid: task.encodedUuid,
				content : props,
			}),
		})
		.then(resp => resp.json())
		.then(resp => {
			if(!resp.error) fetchTask()
		})
	}
	
	const HandleFileUploadSubmit = props => {
		fetch(`/api/v1/file/upload/${task.encodedUuid}`, {
			method: "POST",
			body: props
		})
		.then(resp => resp.json())
		.then(resp => {
			if(!resp.error) fetchTask()
		})
	}

	if(isLoading===true) return 'loading...'
	if(task && 'name' in task){
		return <EditTask updateTask={updateTaskHandler} task={task} HandleFileUploadSubmit={HandleFileUploadSubmit} HandleCommentFormSubmit={HandleCommentFormSubmit}/>
	}
	return 'TODO: 404 for task'
}

export default TaskScreen;