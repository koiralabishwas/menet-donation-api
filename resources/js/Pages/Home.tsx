
type Props = {
    name: string
}

export default function Home({ name }: Props) {
  return (
    <>
      <h1 className='text-xl font-bold text-red-500'>Welcome</h1>
      <p>Hello {name}, welcome to your first Inertia app!</p>
    </>
  )
}
