type Props = {
    project: string;
};

export default function Project({ project }: Props) {
    return <h1>{project}</h1>;
}
