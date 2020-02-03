import React, { Fragment } from 'react'
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import FormLabel from 'react-bootstrap/FormLabel'
import FormText from 'react-bootstrap/FormText'
import Col from 'react-bootstrap/Col'
import Form from 'react-bootstrap/Form'
import FormGroup from 'react-bootstrap/FormGroup'
import FormControl from 'react-bootstrap/FormControl'
import Button from 'react-bootstrap/Button'

function NewProject() {
	const [createProjectName, setCreateProjectName] = React.useState('')
	const [createProjectDueDate, setCreateProjectDueDate] = React.useState('')
	
	const createProject = (e, project) => {
		e.preventDefault()
		console.log(createProjectName, createProjectDueDate)
	}

	return (
		<Fragment>
			<Container>
				<Row>
					<Col>
						<Form onSubmit={createProject}>
							<FormGroup controlId="formProjectName">
								<FormControl type="text" placeholder="Project Name (required)" required={true} onChange={(e)=>setCreateProjectName(e.target.value)} />
								<FormText className="text-muted">
									Enter the name of your new project.
								</FormText>
							</FormGroup>
							<FormGroup>
								<FormControl type="date" placeholder="Due Date" onChange={(e)=>setCreateProjectDueDate(e.target.value)} />
							</FormGroup>
							<Button variant="primary" type="submit">Go</Button>
						</Form>
					</Col>
				</Row>
			</Container>
		</Fragment>
	)
}

export default NewProject;
